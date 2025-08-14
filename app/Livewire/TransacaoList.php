<?php

namespace App\Livewire;

use App\Models\Banco;
use App\Models\CartaoCredito;
use App\Models\Transacao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Adicionar este import
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

class TransacaoList extends Component
{
    #[Url(as: 'ano', history: true)]
    public $ano;

    #[Url(as: 'mes', history: true)]
    public $mes;

    #[Url(as: 'dia', history: true)]
    public $dia;

    public $filtroConta = '';
    public $busca = '';
    public string $sortField = 'data';
    public string $sortDirection = 'desc';

    public function mount()
    {
        $this->ano = request()->query('ano', now()->year);
        $this->mes = request()->query('mes', now()->month);
        $this->dia = request()->query('dia');
    }

    public function sortBy(string $field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $userId = Auth::id();
        $dataAtual = Carbon::createFromDate($this->ano, $this->mes, 1);

        // 1. Busca base de transações do mês para descobrir contas ativas e calcular totais
        $transacoesDoMesQuery = Transacao::where('user_id', $userId)
            ->whereYear('data', $this->ano)
            ->whereMonth('data', $this->mes);
        
        if (trim($this->busca)) {
            $transacoesDoMesQuery->where('descricao', 'like', '%' . $this->busca . '%');
        }
        
        $transacoesDoMes = $transacoesDoMesQuery->get();

        // 2. Descobre quais contas tiveram movimentação
        $activeBancosIds = $transacoesDoMes->where('transacionavel_type', 'App\\Models\\Banco')->pluck('transacionavel_id')->unique();
        $activeCartoesIds = $transacoesDoMes->where('transacionavel_type', 'App\\Models\\CartaoCredito')->pluck('transacionavel_id')->unique();

        // 3. Busca apenas os bancos e cartões ativos para popular o filtro
        $bancosParaFiltro = Banco::whereIn('id', $activeBancosIds)->orderBy('nome')->get();
        $cartoesParaFiltro = CartaoCredito::whereIn('id', $activeCartoesIds)->orderBy('nome')->get();

        // 4. Monta a query final para a lista, aplicando o filtro de conta se houver
        $query = Transacao::where('user_id', $userId)
            ->with('categoria', 'transacionavel')
            ->whereYear('data', $this->ano)
            ->whereMonth('data', $this->mes);

        if ($this->filtroConta) {
            [$tipoConta, $idConta] = explode('_', $this->filtroConta);
            $modeloConta = 'App\\Models\\' . $tipoConta;
            $query->where('transacionavel_type', $modeloConta)->where('transacionavel_id', $idConta);
        }
        if (trim($this->busca)) {
            $query->where('descricao', 'like', '%' . $this->busca . '%');
        }

        $transacoesFiltradas = $query->orderBy($this->sortField, $this->sortDirection)->get();

        // 5. Agrupa os lançamentos (faturas, etc.) a partir da lista já filtrada
        $transacoesDeCartao = $transacoesFiltradas->where('transacionavel_type', CartaoCredito::class);
        $outrasTransacoes = $transacoesFiltradas->where('transacionavel_type', '!=', CartaoCredito::class);
        $faturasDeCartao = $transacoesDeCartao->groupBy('transacionavel_id')
            ->map(function ($gastos, $cartaoId) {
                $cartao = CartaoCredito::find($cartaoId);
                if (!$cartao) return null;
                return (object) [
                    'is_fatura' => true, 
                    'cartao' => $cartao, 
                    'total' => $gastos->sum('valor'), 
                    'transacoes' => $gastos
                ];
            })->filter();

        $lancamentos = $outrasTransacoes->concat($faturasDeCartao->values())
            ->sortByDesc(function ($item) {
                if (isset($item->is_fatura) && $item->transacoes->isNotEmpty()) { return $item->transacoes->first()->data; }
                return $item->data ?? now();
            });
            
        // 6. Calcula os totais a partir da lista NÃO filtrada por conta
        $totalReceitas = $transacoesDoMes->where('tipo', 'receita')->sum('valor');
        $totalDespesas = $transacoesDoMes->where('tipo', 'despesa')->sum('valor');
        $saldoDoMes = $totalReceitas - $totalDespesas;

        $mesesEmPortugues = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ];
        $tituloDoMes = $mesesEmPortugues[$this->mes] . ' de ' . $this->ano;

        // Combina bancos e cartões em uma única coleção para o filtro
        $contas = collect();
        
        // Adiciona bancos com prefixo
        foreach ($bancosParaFiltro as $banco) {
            $contas->push((object) [
                'id' => 'Banco_' . $banco->id,
                'nome' => $banco->nome . ' (Banco)'
            ]);
        }
        
        // Adiciona cartões com prefixo
        foreach ($cartoesParaFiltro as $cartao) {
            $contas->push((object) [
                'id' => 'CartaoCredito_' . $cartao->id,
                'nome' => $cartao->nome . ' (Cartão)'
            ]);
        }

        return view('livewire.transacao-list', [
            'bancos' => $bancosParaFiltro,
            'cartoes' => $cartoesParaFiltro,
            'contas' => $contas,
            'lancamentos' => $lancamentos,
            'dataAtual' => $dataAtual,
            'tituloDoMes' => $tituloDoMes,
            'totalReceitas' => $totalReceitas,
            'totalDespesas' => $totalDespesas,
            'saldoDoMes' => $saldoDoMes,
        ]);
    }
}
