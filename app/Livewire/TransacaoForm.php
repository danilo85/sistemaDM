<?php

namespace App\Livewire;

use App\Models\Transacao;
use Livewire\Component;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransacaoForm extends Component
{
    // Propriedades do formulário
    public ?Transacao $transacao;
    public string $tipo = 'despesa';
    public string $descricao = '';
    public $valor = '';
    public $data;
    public $categoria_id = '';
    public $conta = '';
    
    // Propriedades para Frequência
    public string $launchType = 'unico'; // 'unico', 'parcelado', 'recorrente'
    public ?int $parcela_total = 2;
    public string $frequency = 'monthly';

    // Propriedades para os selects
    public Collection $categorias;
    public Collection $bancos;
    public Collection $cartoes;

    public function mount(Transacao $transacao, Collection $categorias, Collection $bancos, Collection $cartoes, $ano = null, $mes = null)
    {
        $this->transacao = $transacao;
        $this->categorias = $categorias;
        $this->bancos = $bancos;
        $this->cartoes = $cartoes;
        
        if ($this->transacao->exists || !empty($this->transacao->descricao)) {
            $this->tipo = $this->transacao->tipo;
            $this->descricao = $this->transacao->descricao;
            $this->valor = $this->transacao->valor;
            $this->data = $this->transacao->data ? $this->transacao->data->format('d/m/Y') : now()->format('d/m/Y');
            $this->categoria_id = $this->transacao->categoria_id;
            if ($this->transacao->transacionavel) {
                $this->conta = class_basename($this->transacao->transacionavel_type) . '_' . $this->transacao->transacionavel_id;
            }
            if ($this->transacao->is_recurring) { // CORREÇÃO: Usando a propriedade correta
                $this->launchType = 'recorrente';
                $this->frequency = $this->transacao->frequency;
            } elseif ($this->transacao->compra_id) {
                $this->launchType = 'parcelado';
                $this->parcela_total = $this->transacao->parcela_total;
            } else {
                $this->launchType = 'unico';
            }
        } else {
            $anoAtual = $ano ?? now()->year;
            $mesAtual = $mes ?? now()->month;
            $dia = min(now()->day, Carbon::create($anoAtual, $mesAtual)->daysInMonth);
            $this->data = Carbon::createFromDate($anoAtual, $mesAtual, $dia)->format('d/m/Y');
        }
    }

    public function updatedTipo($value)
    {
        $this->categoria_id = '';
    }

    public function save()
    {
        $valorLimpo = str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $this->valor ?? '0'));

        $validated = $this->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required',
            'data' => 'required|date_format:d/m/Y',
            'tipo' => 'required|string|in:receita,despesa',
            'categoria_id' => 'required|exists:categorias,id',
            'conta' => 'required|string',
            'launchType' => 'required|string|in:unico,parcelado,recorrente',
            'parcela_total' => 'nullable|required_if:launchType,parcelado|integer|min:2',
            'frequency' => 'nullable|required_if:launchType,recorrente|string|in:monthly,yearly',
        ]);
        
        $validated['valor'] = $valorLimpo;

        $dataParaSalvar = Carbon::createFromFormat('d/m/Y', $validated['data']);
        [$tipoConta, $idConta] = explode('_', $validated['conta']);
        $modeloConta = 'App\\Models\\' . $tipoConta;

        $dadosBase = [
            'user_id' => Auth::id(),
            'descricao' => $validated['descricao'],
            'data' => $dataParaSalvar->format('Y-m-d'),
            'tipo' => $validated['tipo'],
            'categoria_id' => $validated['categoria_id'],
            'transacionavel_id' => $idConta,
            'transacionavel_type' => $modeloConta,
            'pago' => false,
        ];

        if ($this->launchType === 'parcelado') {
            $valorParcela = $validated['valor'] / $validated['parcela_total'];
            $compraId = Str::uuid();

            for ($i = 1; $i <= $validated['parcela_total']; $i++) {
                Transacao::create(array_merge($dadosBase, [
                    'valor' => $valorParcela,
                    'parcela_atual' => $i,
                    'parcela_total' => $validated['parcela_total'],
                    'compra_id' => $compraId,
                    'data' => $dataParaSalvar->copy()->addMonthsNoOverflow($i - 1)->format('Y-m-d'),
                ]));
            }
        } elseif ($this->launchType === 'recorrente') {
            Transacao::create(array_merge($dadosBase, [
                'valor' => $validated['valor'],
                'is_recurring' => true, // CORREÇÃO CRÍTICA AQUI
                'frequency' => $validated['frequency'],
            ]));
        } else {
            Transacao::create(array_merge($dadosBase, ['valor' => $validated['valor']]));
        }

        session()->flash('success', 'Lançamento salvo com sucesso!');

        return redirect()->route('transacoes.index', [
            'ano' => $dataParaSalvar->year,
            'mes' => $dataParaSalvar->month,
        ]);
    }

    public function render()
    {
        $categoriasFiltradas = $this->categorias->where('tipo', $this->tipo);

        return view('livewire.transacao-form', [
            'categoriasFiltradas' => $categoriasFiltradas
        ]);
    }
}
