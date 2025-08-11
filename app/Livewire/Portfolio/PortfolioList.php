<?php

namespace App\Livewire\Portfolio;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class PortfolioList extends Component
{
    use WithPagination;

    protected $listeners = ['portfolioUpdated' => '$refresh', 'searchUpdated'];

    public $filtroCategoria = '';
    public $busca = '';
    public $filtroStatus = ''; // '' = todos, '1' = ativados, '0' = desativados
    public $isLoading = true;
    public $skeletonCount = 12; // Número padrão de esqueletos

    public function toggleActive(Portfolio $portfolio)
    {
        $portfolio->update(['is_active' => !$portfolio->is_active]);
        $this->dispatch('notify', ['message' => 'Status do trabalho atualizado!', 'type' => 'info']);
    }

    public function delete(Portfolio $portfolio)
    {
        $portfolio->delete();
        $this->dispatch('notify', ['message' => 'Trabalho excluído com sucesso!', 'type' => 'info']);
    }

    public function toggleFeatured(Portfolio $portfolio)
    {
        $portfolio->update(['featured' => !$portfolio->featured]);
        $message = $portfolio->featured ? 'Trabalho marcado como destaque!' : 'Trabalho removido dos destaques!';
        $this->dispatch('notify', ['message' => $message, 'type' => 'success']);
    }

    public function updateOrder($portfolioId, $newPosition)
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->findOrFail($portfolioId);
        
        // Obter todos os portfolios do usuário ordenados pela posição atual
        $portfolios = Portfolio::where('user_id', Auth::id())
            ->orderBy('order_position')
            ->orderBy('id')
            ->get();
        
        // Remover o item da lista
        $portfolios = $portfolios->reject(function($item) use ($portfolioId) {
            return $item->id == $portfolioId;
        })->values();
        
        // Inserir o item na nova posição
        $portfolios->splice($newPosition, 0, [$portfolio]);
        
        // Atualizar as posições de todos os itens
        foreach ($portfolios as $index => $item) {
            $item->update(['order_position' => $index]);
        }
        
        $this->dispatch('notify', ['message' => 'Ordem dos trabalhos atualizada!', 'type' => 'success']);
    }

    public function mount()
    {
        // Inicia com loading true, será alterado via JavaScript após delay
        $this->isLoading = true;
    }

    public function finishLoading()
    {
        $this->isLoading = false;
    }
    
    public function searchUpdated($searchTerm)
    {
        $this->busca = $searchTerm;
        $this->resetPage();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $query = Portfolio::where('user_id', Auth::id())
            ->with(['category', 'thumb'])
            ->orderBy('order_position')
            ->latest('post_date');

        if ($this->filtroCategoria) {
            $query->where('portfolio_category_id', $this->filtroCategoria);
        }

        if (trim($this->busca)) {
            $query->where('title', 'like', '%' . $this->busca . '%');
        }

        if ($this->filtroStatus !== '') {
            $query->where('is_active', (bool) $this->filtroStatus);
        }

        // Conta o total de resultados antes da paginação
        $totalResults = $query->count();
        
        $portfolios = $query->paginate(12);
        
        // Calcula o número de esqueletos baseado no número real de itens
        if ($totalResults == 0) {
            $this->skeletonCount = 0; // Sem itens, sem esqueletos
        } else {
            // Calcula quantos itens estarão na página atual
            $currentPage = $portfolios->currentPage();
            $perPage = $portfolios->perPage();
            $remainingItems = $totalResults - (($currentPage - 1) * $perPage);
            $this->skeletonCount = min($remainingItems, $perPage);
        }

        return view('livewire.portfolio.portfolio-list', [
            'portfolios' => $portfolios,
            'categories' => PortfolioCategory::where('user_id', Auth::id())
                ->whereHas('portfolios', function($q) {
                    $q->where('user_id', Auth::id());
                })
                ->get(),
        ]);
    }
}
