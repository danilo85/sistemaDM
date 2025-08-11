<?php

namespace App\Livewire\Portfolio;

use Livewire\Component;

class PortfolioSearch extends Component
{
    public $busca = '';
    
    protected $listeners = ['resetSearch'];
    
    public function updatedBusca()
    {
        $this->dispatch('searchUpdated', $this->busca);
    }
    
    public function clearSearch()
    {
        $this->busca = '';
        $this->dispatch('searchUpdated', $this->busca);
    }
    
    public function resetSearch()
    {
        $this->busca = '';
    }
    
    public function render()
    {
        return view('livewire.portfolio.portfolio-search');
    }
}