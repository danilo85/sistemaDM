<?php

namespace App\Livewire\Portfolio;

use App\Models\Orcamento;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Models\PortfolioImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class PortfolioForm extends Component
{
    use WithFileUploads;

    public ?Portfolio $portfolio = null;

    // Propriedades separadas para o formulário
    public $title = '';
    public $portfolio_category_id = '';
    public $post_date = '';
    public $description = '';
    public $external_link = '';
    public $link_legend = '';
    public $orcamento_id = null;

    public $thumb;
    public $images = [];
    public $existingImages = [];
    public $returnUrl;

    public function mount($portfolio = null, $orcamento_id = null)
    {
        // Caso 1: Editando um trabalho existente
        if ($portfolio && $portfolio instanceof Portfolio) {
            $this->portfolio = $portfolio;
            $this->title = $portfolio->title;
            $this->portfolio_category_id = $portfolio->portfolio_category_id;
            $this->post_date = $portfolio->post_date->format('d/m/Y');
            $this->description = $portfolio->description;
            $this->external_link = $portfolio->external_link;
            $this->link_legend = $portfolio->link_legend;
            $this->existingImages = $portfolio->images()->where('is_thumb', false)->orderBy('order_position')->get();
            // Se está editando, volta para Meus Trabalhos
            $this->returnUrl = route('portfolio.index');
        } 
        // Caso 2: Criando a partir do Pipeline
        elseif ($orcamento_id) {
            $this->portfolio = new Portfolio();
            $orcamento = Orcamento::findOrFail($orcamento_id);
            $this->orcamento_id = $orcamento->id;
            $this->title = $orcamento->titulo;
            $this->description = $orcamento->descricao;
            $this->post_date = now()->format('d/m/Y');
            // Se veio do Pipeline, volta para Pipeline
            $this->returnUrl = route('portfolio.pipeline.index');
        } 
        // Caso 3: Criando um trabalho do zero
        else {
            $this->portfolio = new Portfolio();
            $this->post_date = now()->format('d/m/Y');
            // Se veio de Meus Trabalhos, volta para Meus Trabalhos
            $this->returnUrl = route('portfolio.index');
        }
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'portfolio_category_id' => 'required|exists:portfolio_categories,id',
            'post_date' => 'required|date',
            'description' => 'required|string',
            'external_link' => 'nullable|url',
            'link_legend' => 'nullable|string|max:255',
            'thumb' => 'nullable|image|max:10240',
            'images.*' => 'image|max:10240',
        ];
    }

    public function save()
    {
        $this->validate();

        // Converte a data do formato d/m/Y para Y-m-d
        $postDate = \Carbon\Carbon::createFromFormat('d/m/Y', $this->post_date)->format('Y-m-d');

        $data = [
            'user_id' => Auth::id(),
            'orcamento_id' => $this->orcamento_id,
            'title' => $this->title,
            'portfolio_category_id' => $this->portfolio_category_id,
            'post_date' => $postDate,
            'description' => $this->description,
            'external_link' => $this->external_link,
            'link_legend' => $this->link_legend,
        ];

        if ($this->portfolio->exists) {
            $this->portfolio->update($data);
        } else {
            $this->portfolio = Portfolio::create($data);
        }
        
        $optimizerChain = OptimizerChainFactory::create();

        if ($this->thumb) {
            if ($this->portfolio->thumb) {
                $this->portfolio->thumb->delete();
            }
            $path = $this->thumb->store('portfolio', 'public');
            $optimizerChain->optimize(storage_path('app/public/' . $path));
            $this->portfolio->images()->create(['path' => $path, 'is_thumb' => true]);
        }

        if ($this->images) {
            foreach ($this->images as $image) {
                $path = $image->store('portfolio', 'public');
                $optimizerChain->optimize(storage_path('app/public/' . $path));
                $this->portfolio->images()->create(['path' => $path, 'is_thumb' => false]);
            }
        }

        $this->dispatch('notify', ['message' => 'Trabalho salvo com sucesso!', 'type' => 'success']);
        return redirect()->route('portfolio.index'); 
    }

    public function deleteImage($imageId)
    {
        $image = PortfolioImage::findOrFail($imageId);
        
        // Verifica se a imagem pertence ao portfólio atual
        if ($image->portfolio_id !== $this->portfolio->id) {
            $this->dispatch('notify', ['message' => 'Erro: Imagem não encontrada!', 'type' => 'error']);
            return;
        }

        // Remove o arquivo físico
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        // Remove do banco de dados
        $image->delete();

        // Atualiza a lista de imagens existentes
        $this->existingImages = $this->portfolio->images()->where('is_thumb', false)->get();

        $this->dispatch('notify', ['message' => 'Imagem removida com sucesso!', 'type' => 'success']);
    }

    public function updateImageOrder($imageIds)
    {
        foreach ($imageIds as $index => $imageId) {
            PortfolioImage::where('id', $imageId)
                ->where('portfolio_id', $this->portfolio->id)
                ->update(['order_position' => $index + 1]);
        }

        // Atualiza a lista de imagens existentes com a nova ordem
        $this->existingImages = $this->portfolio->images()
            ->where('is_thumb', false)
            ->orderBy('order_position')
            ->get();

        $this->dispatch('notify', ['message' => 'Ordem das imagens atualizada!', 'type' => 'success']);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.portfolio.portfolio-form', [
            'categories' => PortfolioCategory::where('user_id', Auth::id())->get(),
        ]);
    }
}
