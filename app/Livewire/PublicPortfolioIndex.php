<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Models\Service;
use App\Models\Testimonial;
use Livewire\WithPagination;

class PublicPortfolioIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = '';
    public $showFilters = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
    ];

    public function mount()
    {
        // Inicialização do componente
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedCategory = '';
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function viewWork($slug)
    {
        return $this->redirect(route('portfolio.public.show', $slug));
    }

    public function getWorksProperty()
    {
        $query = Portfolio::with(['category', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('client', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        return $query->paginate(12);
    }

    public function getCategoriesProperty()
    {
        return PortfolioCategory::orderBy('name')
            ->get();
    }

    public function getServicesProperty()
    {
        return Service::active()
            ->ordered()
            ->take(6)
            ->get();
    }

    public function getTestimonialsProperty()
    {
        return Testimonial::active()
            ->latest()
            ->take(3)
            ->get();
    }

    public function getFeaturedWorksProperty()
    {
        return Portfolio::with(['category', 'images'])
            ->where('is_active', true)
            ->where('featured', true)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
    }

    public function render()
    {
        return view('livewire.public-portfolio-index', [
            'works' => $this->works,
            'categories' => $this->categories,
            'services' => $this->services,
            'testimonials' => $this->testimonials,
            'featuredWorks' => $this->featuredWorks,
        ])->layout('layouts.portfolio');
    }
}