<?php

namespace App\Livewire;

use App\Models\Autor;
use App\Models\Cliente;
use App\Models\Orcamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class OrcamentoCreateForm extends Component
{
    // Propriedades para os campos do formulário
    public $cliente_id;
    public $autores = [];
    public $titulo;
    public $descricao;
    public $valor_total;
    public $prazo_entrega_dias;
    public $data_emissao;
    public $data_validade;
    public $condicoes_pagamento;
    public string $sugestao = '';

    public $fromOrcamentoId;

    public function mount($fromOrcamentoId = null)
    {
        $this->fromOrcamentoId = $fromOrcamentoId;

        if ($this->fromOrcamentoId) {
            $original = Orcamento::with('autores')->find($this->fromOrcamentoId);
            if ($original) {
                $this->cliente_id = $original->cliente_id;
                $this->autores = $original->autores->pluck('id')->toArray();
                $this->titulo = $original->titulo . ' (Cópia)';
                $this->descricao = $original->descricao;
                $this->valor_total = $original->valor_total;
                $this->prazo_entrega_dias = $original->prazo_entrega_dias;
                $this->condicoes_pagamento = $original->condicoes_pagamento;
                $this->data_emissao = now()->format('d/m/Y');
                $this->data_validade = now()->addDays(10)->format('d/m/Y');
            }
        } else {
            $this->data_emissao = now()->format('d/m/Y');
            $this->data_validade = now()->addDays(10)->format('d/m/Y');
            $this->condicoes_pagamento = '50% para iniciar e 50% na entrega final do projeto.';
        }
    }

    public function save()
    {
        // Limpa a máscara de moeda
        $this->valor_total = is_string($this->valor_total) ? str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $this->valor_total)) : $this->valor_total;

        $this->validate([
            'cliente_id' => 'required',
            'autores' => 'required|array|min:1',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'valor_total' => 'required|numeric|min:0',
            'prazo_entrega_dias' => 'nullable|integer',
            'data_emissao' => 'required|date_format:d/m/Y',
            'data_validade' => 'required|date_format:d/m/Y',
            'condicoes_pagamento' => 'nullable|string',
        ]);
        
        $clienteId = $this->cliente_id;
        if (!is_numeric($clienteId)) {
            // CORREÇÃO AQUI: Adicionamos o user_id ao criar o cliente
            $novoCliente = Cliente::create([
                'name' => $clienteId, 
                'is_complete' => false,
                'user_id' => Auth::id()
            ]);
            $clienteId = $novoCliente->id;
        }

        $autorIds = [];
        foreach ($this->autores as $autorInput) {
            if (is_numeric($autorInput)) {
                $autorIds[] = $autorInput;
            } else {
                // CORREÇÃO AQUI: Adicionamos o user_id ao criar o autor
                $novoAutor = Autor::create([
                    'name' => $autorInput, 
                    'is_complete' => false,
                    'user_id' => Auth::id()
                ]);
                $autorIds[] = $novoAutor->id;
            }
        }
        
        $orcamento = Orcamento::create([
            'cliente_id' => $clienteId,
            'user_id' => Auth::id(),
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'valor_total' => $this->valor_total,
            'prazo_entrega_dias' => $this->prazo_entrega_dias,
            'data_emissao' => Carbon::createFromFormat('d/m/Y', $this->data_emissao)->format('Y-m-d'),
            'data_validade' => Carbon::createFromFormat('d/m/Y', $this->data_validade)->format('Y-m-d'),
            'condicoes_pagamento' => $this->condicoes_pagamento,
            'status' => 'Analisando',
            'numero' => (Orcamento::max('numero') ?? 0) + 1,
        ]);

        $orcamento->autores()->attach($autorIds);

        return redirect()->route('orcamentos.index')
                         ->with('success', 'Orçamento Nº ' . $orcamento->numero . ' criado com sucesso!');
    }

    public function render()
    {
        return view('livewire.orcamento-create-form', [
            'clientes' => Cliente::orderBy('name')->get(),
            'autoresDisponiveis' => Autor::orderBy('name')->get(),
        ]);
    }

    public function updatedDescricao(string $value)
    {
        if (strlen($value) < 15) {
            $this->sugestao = '';
            return;
        }

        $keywords = collect(explode(' ', $value))->filter(fn ($word) => strlen($word) > 3);

        if ($keywords->isEmpty()) {
            $this->sugestao = '';
            return;
        }

        // CORREÇÃO: A busca agora filtra apenas os orçamentos do usuário logado
        $query = Orcamento::where('user_id', Auth::id())
                          ->whereIn('status', ['Aprovado', 'Pago', 'Finalizado']);

        $keywords->each(function ($keyword) use ($query) {
            $query->where('descricao', 'like', '%' . $keyword . '%');
        });

        $orcamentosSimilares = $query->take(10)->get();

        if ($orcamentosSimilares->count() > 0) {
            $mediaValor = $orcamentosSimilares->avg('valor_total');
            $mediaPrazo = $orcamentosSimilares->avg('prazo_entrega_dias');

            $this->sugestao = sprintf(
                '💡 **Sugestão:** Para %d projetos similares encontrados, a média de valor foi de **R$ %s** e o prazo médio de **%d dias**.',
                $orcamentosSimilares->count(),
                number_format($mediaValor, 2, ',', '.'),
                round($mediaPrazo)
            );
        } else {
            $this->sugestao = '';
        }
    }
}
