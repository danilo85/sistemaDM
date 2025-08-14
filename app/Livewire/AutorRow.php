<?php

namespace App\Livewire;

use App\Models\Autor;
use Livewire\Component;

class AutorRow extends Component
{
    public Autor $autor;

    // Ação para deletar o autor
    public function deleteAutor()
    {
        $this->autor->delete();
        // Avisa o componente "pai" (a lista) para se atualizar
        $this->dispatch('autorExcluido', 'Autor excluído com sucesso!');
    }

    public function render()
    {
        return view('livewire.autor.autor-row');
    }
}