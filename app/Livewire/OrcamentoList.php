<?php

namespace App\Livewire;

use App\Models\Orcamento;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class OrcamentoList extends Component
{
    use WithPagination;

    // Propriedades para filtros e ordenação
    public string $filtroStatus = 'ativos';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';
    public string $busca = '';

    // Propriedade para o modo de visualização
    public string $viewMode = 'grid'; // 'grid' ou 'list'

    // Listener para o evento de exclusão
    #[On('orcamento-excluido')]
    public function placeholder()
    {
        // Apenas para forçar a re-renderização
    }

    /**
     * O método mount é executado quando o componente é carregado.
     */
    public function mount()
    {
        // Pega a visualização preferida da sessão, com 'grid' como padrão
        $this->viewMode = session('orcamento_view_mode', 'grid');
    }

    /**
     * Altera e salva o modo de visualização.
     */
    public function setViewMode(string $mode)
    {
        if (in_array($mode, ['grid', 'list'])) {
            $this->viewMode = $mode;
            // Guarda a preferência na sessão do usuário
            session(['orcamento_view_mode' => $mode]);
        }
    }

    /**
     * Define o filtro de status.
     */
    public function setFiltroStatus(string $status)
    {
        $this->filtroStatus = $status;
        $this->resetPage(); // Reseta a paginação ao aplicar um novo filtro
    }

    /**
     * Define a ordenação da tabela.
     */
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
        // CORREÇÃO: A busca agora começa filtrando pelo user_id do usuário logado
        $query = Orcamento::where('orcamentos.user_id', Auth::id())
            ->join('clientes', 'orcamentos.cliente_id', '=', 'clientes.id')
            ->select('orcamentos.*', 'clientes.name as cliente_name');

        // Aplica o filtro de status
        if ($this->filtroStatus === 'ativos') {
            $query->whereIn('orcamentos.status', ['Analisando', 'Aprovado', 'Pago']);
        } elseif ($this->filtroStatus) {
            $query->where('orcamentos.status', $this->filtroStatus);
        }

        // Aplica a busca
        if ($this->busca) {
            $query->where(function ($q) {
                $q->where('orcamentos.titulo', 'like', '%' . $this->busca . '%')
                  ->orWhere('clientes.name', 'like', '%' . $this->busca . '%');
            });
        }

        // Aplica a ordenação
        $query->orderBy($this->sortField, $this->sortDirection);

        // Define quantos itens por página, dependendo da visualização
        $perPage = $this->viewMode === 'grid' ? 12 : 15;
        $orcamentos = $query->with('autores')->paginate($perPage);

        // CORREÇÃO: O cálculo do total a receber também precisa ser filtrado pelo usuário
        $totalAReceber = Orcamento::where('user_id', Auth::id())
            ->whereIn('status', ['Analisando', 'Aprovado'])
            ->sum('valor_total');
        
        // Define a lista de status para os botões de filtro
        $statusDisponiveis = ['Analisando', 'Aprovado', 'Rejeitado', 'Finalizado', 'Pago'];

        return view('livewire.orcamento-list', [
            'orcamentos' => $orcamentos,
            'statusDisponiveis' => $statusDisponiveis,
            'totalAReceber' => $totalAReceber,
        ]);
    }
}
