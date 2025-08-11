<?php

namespace App\Livewire\Portfolio;

use App\Models\PortfolioCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class CategoryManager extends Component
{
    use WithPagination;

    public ?PortfolioCategory $editing = null;
    public bool $showModal = false;
    public string $name = ''; // Variável separada para o formulário

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function newCategory()
    {
        $this->editing = new PortfolioCategory();
        $this->name = ''; // Limpa o campo
        $this->showModal = true;
    }

    public function edit(PortfolioCategory $category)
    {
        $this->editing = $category;
        $this->name = $category->name; // Preenche o campo com o nome existente
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $this->editing->user_id = Auth::id();
        $this->editing->name = $this->name; // Atribui o nome validado
        $this->editing->save();
        
        $this->showModal = false;
        $this->dispatch('notify', ['message' => 'Categoria salva com sucesso!', 'type' => 'success']);
    }

    public function delete(PortfolioCategory $category)
    {
        $category->delete();
        $this->dispatch('notify', ['message' => 'Categoria excluída com sucesso!', 'type' => 'info']);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.portfolio.category-manager', [
            'categories' => PortfolioCategory::where('user_id', Auth::id())->paginate(10),
        ]);
    }
}
