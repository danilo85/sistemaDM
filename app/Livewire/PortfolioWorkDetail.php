<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Testimonial;

class PortfolioWorkDetail extends Component
{
    public Portfolio $work;
    public $currentImageIndex = 0;
    public $showImageModal = false;

    public function mount($slug)
    {
        $this->work = Portfolio::with(['category', 'images'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function openImageModal($index)
    {
        $this->currentImageIndex = $index;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
    }

    public function nextImage()
    {
        $totalImages = $this->work->images->count();
        $this->currentImageIndex = ($this->currentImageIndex + 1) % $totalImages;
    }

    public function previousImage()
    {
        $totalImages = $this->work->images->count();
        $this->currentImageIndex = ($this->currentImageIndex - 1 + $totalImages) % $totalImages;
    }

    public function getRelatedWorksProperty()
    {
        return Portfolio::with(['category', 'images'])
            ->where('is_active', true)
            ->where('portfolio_category_id', $this->work->portfolio_category_id)
            ->where('id', '!=', $this->work->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }

    public function getServicesProperty()
    {
        return Service::active()
            ->ordered()
            ->take(3)
            ->get();
    }

    public function getTestimonialsProperty()
    {
        return Testimonial::active()
            ->latest()
            ->take(2)
            ->get();
    }

    public function render()
    {
        return view('livewire.portfolio-work-detail', [
            'relatedWorks' => $this->relatedWorks,
            'services' => $this->services,
            'testimonials' => $this->testimonials,
        ])->layout('layouts.portfolio');
    }
}