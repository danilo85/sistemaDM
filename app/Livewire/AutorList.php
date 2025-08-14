<?php

namespace App\Livewire;

use App\Models\Autor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

class AutorList extends Component
{
    use WithPagination;

    // Adicione este listener
    #[On('autorExcluido')]
    public function placeholder()
    {
        // Este método vazio força o componente a se re-renderizar
    }

    // Conecta as propriedades com a URL
    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'ordenar_por')]
    public string $sortField = 'name';

    #[Url(as: 'direcao')]
    public string $sortDirection = 'asc';

    public function sortBy(string $field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateCor($autorId, $cor)
    {
        $autor = Autor::find($autorId);
        if ($autor) {
            $autor->update(['cor' => $cor]);
            $this->dispatch('corAtualizada', ['autorId' => $autorId, 'cor' => $cor]);
        }
    }

    public function deleteAutor($autorId)
    {
        $autor = Autor::find($autorId);
        if ($autor) {
            $autor->delete();
            session()->flash('message', 'Autor excluído com sucesso!');
        }
    }

    public function render()
    {
        $query = Autor::where('user_id', auth()->id());

        // Aplica a busca se houver texto
        if (trim($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('contact', 'like', '%' . $this->search . '%');
            });
        }

        $autores = $query->orderBy($this->sortField, $this->sortDirection)->paginate(12);

        return view('livewire.autor.autor-list', [
            'autores' => $autores,
        ]);
    }
}
