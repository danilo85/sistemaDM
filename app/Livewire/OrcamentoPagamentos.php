<?php

namespace App\Livewire;

use App\Models\Banco;
use App\Models\CartaoCredito;
use App\Models\Categoria;
use App\Models\Orcamento;
use App\Models\Pagamento;
use App\Models\Receipt;
use App\Models\Transacao;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\On;

class OrcamentoPagamentos extends Component
{
    public Orcamento $orcamento;

    // Propriedades para o formulário
    public $valor;
    public $data_pagamento;
    public $tipo_pagamento = 'PIX';
    public $conta_id;

    public ?Pagamento $pagamentoEmEdicao = null;

    // Define as regras de validação para o formulário
    protected function rules()
    {
        $totalPagoAnteriormente = $this->pagamentoEmEdicao
            ? $this->orcamento->pagamentos()->where('id', '!=', $this->pagamentoEmEdicao->id)->sum('valor_pago')
            : $this->orcamento->pagamentos()->sum('valor_pago');

        $restante = $this->orcamento->valor_total - $totalPagoAnteriormente;
        $maximoPermitido = max(0, $restante);

        return [
            'valor' => "required|numeric|min:0.01|max:{$maximoPermitido}",
            'data_pagamento' => 'required|date_format:d/m/Y',
            'tipo_pagamento' => 'required|string|max:255',
            'conta_id' => 'required|string',
        ];
    }

        public function generateReceipt($pagamentoId)
        {
            // 1. Encontra o pagamento ou falha se não existir
            $pagamento = Pagamento::findOrFail($pagamentoId);

            // 2. Procura por um recibo existente para evitar duplicatas
            $receipt = Receipt::where('pagamento_id', $pagamento->id)->first();

            // 3. Se o recibo não existir, cria um novo
            if (! $receipt) {
                // Lógica para gerar um número de recibo sequencial (Ex: 2025-0001)
                $lastReceipt = Receipt::whereYear('created_at', date('Y'))->latest('id')->first();
                $nextNumber = $lastReceipt ? (int)substr($lastReceipt->numero, 5) + 1 : 1;
                $receiptNumber = date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                $receipt = Receipt::create([
                    'user_id'       => Auth::id(), // CORREÇÃO AQUI
                    'pagamento_id'  => $pagamento->id,
                    'numero'        => $receiptNumber,
                    'data_emissao'  => now(),
                    'valor'         => $pagamento->valor_pago,
                    'token'         => Str::random(40),
                ]);
            }

            // 4. Gera a URL pública para o recibo
            $url = route('receipts.public.show', ['token' => $receipt->token]);

            // 5. Envia um evento para o navegador abrir a URL em uma nova aba
            $this->dispatch('open-new-tab', url: $url);
        }

    // Ouve o evento para se auto-atualizar após uma ação
    #[On('pagamento-adicionado')]
    public function placeholder() {}

    // Define uma conta padrão ao carregar
    public function mount()
    {
        $bancos = Banco::orderBy('nome')->get();
        if ($bancos->isNotEmpty()) {
            $this->conta_id = 'Banco_' . $bancos->first()->id;
        }
        $this->data_pagamento = now()->format('d/m/Y');
    }

    public function quitarOrcamento()
    {
        $totalPago = $this->orcamento->pagamentos()->sum('valor_pago');
        $restante = $this->orcamento->valor_total - $totalPago;

        if ($restante > 0) {
            // Prepara os dados para o pagamento de quitação
            $dadosQuitacao = [
                'valor' => $restante,
                'data_pagamento' => now()->format('d/m/Y'),
                'tipo_pagamento' => 'Quitação',
                'conta_id' => $this->conta_id, // Usa a conta selecionada ou a padrão
            ];

            // Usa a mesma lógica de criação de pagamento
            $this->criarPagamento($dadosQuitacao);

            // CORREÇÃO: Lógica de atualização de status adicionada
            $this->orcamento->status = 'Pago';
            $this->orcamento->save();
            $this->dispatch('status-atualizado'); // Notifica o componente de status

            $this->dispatch('pagamento-adicionado', 'Orçamento quitado com sucesso!');
        }
    }
    // Carrega dados no formulário para edição
    public function editarPagamento(Pagamento $pagamento)
    {
        $this->pagamentoEmEdicao = $pagamento;
        $this->valor = number_format($pagamento->valor_pago, 2, ',', '.');
        $this->data_pagamento = $pagamento->data_pagamento->format('d/m/Y');
        $this->tipo_pagamento = $pagamento->tipo_pagamento;
        $this->conta_id = class_basename($pagamento->transacao->transacionavel_type) . '_' . $pagamento->transacao->transacionavel_id;
    }

    // Limpa o formulário e sai do modo de edição
    public function cancelarEdicao()
    {
        $this->reset('valor', 'data_pagamento', 'tipo_pagamento', 'conta_id', 'pagamentoEmEdicao');
        $this->mount(); // Volta a conta para o padrão
    }

    public function excluirPagamento(Pagamento $pagamento)
    {
        $statusAtual = $this->orcamento->status;

        DB::transaction(function () use ($pagamento) {
            optional($pagamento->transacao)->delete();
            $pagamento->delete();
        });

        $this->orcamento->refresh();
        $totalPagoAgora = $this->orcamento->pagamentos()->sum('valor_pago');

        if (strtolower($statusAtual) === 'pago' && $totalPagoAgora < $this->orcamento->valor_total) {
            $this->orcamento->status = 'Aprovado';
            $this->orcamento->save();
        }

        $this->dispatch('pagamento-adicionado', 'Pagamento excluído com sucesso!');
        $this->dispatch('status-atualizado');
    }

    // Método principal que decide se cria ou atualiza um pagamento
    public function salvarPagamento()
    {
        if ($this->valor) {
            $this->valor = str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $this->valor));
        }
        
        $validated = $this->validate();

        if ($this->pagamentoEmEdicao) {
            $this->atualizarPagamento($validated, $this->pagamentoEmEdicao);
        } else {
            $this->criarPagamento($validated);
        }

        $this->orcamento->refresh();
        $totalPago = $this->orcamento->pagamentos()->sum('valor_pago');

        if ($totalPago >= $this->orcamento->valor_total) {
            if($this->orcamento->status != 'Pago') {
                $this->orcamento->status = 'Pago';
                $this->orcamento->save();
                $this->dispatch('status-atualizado');
            }
        }

        $this->cancelarEdicao();
        $this->dispatch('pagamento-adicionado', 'Ação concluída com sucesso!');
    }
    
    private function criarPagamento(array $validatedData)
        {
            DB::transaction(function () use ($validatedData) {
                [$tipoConta, $idConta] = explode('_', $this->conta_id);
                $modeloConta = 'App\\Models\\' . $tipoConta;
                $dataParaSalvar = Carbon::createFromFormat('d/m/Y', $validatedData['data_pagamento'])->format('Y-m-d');
                
                // CORREÇÃO AQUI: Adicionamos o user_id ao criar a categoria
                $categoria = Categoria::firstOrCreate(
                    ['nome' => 'Receita de Projetos', 'user_id' => Auth::id()],
                    ['tipo' => 'receita']
                );

                $transacao = Transacao::create([
                    'user_id' => Auth::id(),
                    'descricao' => "Pagamento: {$validatedData['tipo_pagamento']} - Orçamento #{$this->orcamento->numero}",
                    'valor' => $validatedData['valor'],
                    'data' => $dataParaSalvar,
                    'tipo' => 'receita',
                    'categoria_id' => $categoria->id,
                    'transacionavel_id' => $idConta,
                    'transacionavel_type' => $modeloConta,
                    'pago' => true,
                ]);

                Pagamento::create([
                    'orcamento_id' => $this->orcamento->id,
                    'transacao_id' => $transacao->id,
                    'valor_pago' => $validatedData['valor'],
                    'data_pagamento' => $dataParaSalvar,
                    'tipo_pagamento' => $validatedData['tipo_pagamento'],
                ]);
            });
        }
    
    private function atualizarPagamento(array $validatedData, Pagamento $pagamento)
    {
        // O método de atualização não precisa mudar, pois a transação não troca de dono.
        DB::transaction(function () use ($validatedData, $pagamento) {
            [$tipoConta, $idConta] = explode('_', $this->conta_id);
            $modeloConta = 'App\\Models\\' . $tipoConta;
            $dataParaSalvar = Carbon::createFromFormat('d/m/Y', $validatedData['data_pagamento'])->format('Y-m-d');

            optional($pagamento->transacao)->update([
                'valor' => $validatedData['valor'],
                'data' => $dataParaSalvar,
                'transacionavel_id' => $idConta,
                'transacionavel_type' => $modeloConta,
            ]);

            $pagamento->update([
                'valor_pago' => $validatedData['valor'],
                'data_pagamento' => $dataParaSalvar,
                'tipo_pagamento' => $validatedData['tipo_pagamento'],
            ]);
        });
    }

    public function render()
    {
        $pagamentos = $this->orcamento->pagamentos()->with('transacao.transacionavel')->latest('data_pagamento')->get();
        $bancos = Banco::orderBy('nome')->get();
        $cartoes = CartaoCredito::orderBy('nome')->get();
        
        $totalPago = $pagamentos->sum('valor_pago');
        $restante = $this->orcamento->valor_total - $totalPago;

        return view('livewire.orcamento-pagamentos', [
            'pagamentos' => $pagamentos,
            'bancos' => $bancos,
            'cartoes' => $cartoes,
            'totalPago' => $totalPago,
            'restante' => $restante,
        ]);

        // CORREÇÃO: Linha duplicada removida
    }
}
