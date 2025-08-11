<?php

namespace App\Livewire;

use App\Models\Autor;
use Illuminate\Support\Facades\Auth; // Adicionar este import
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

class AutorList extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $busca = '';

    #[Url(as: 'ordenar_por')]
    public string $sortField = 'name';

    #[Url(as: 'direcao')]
    public string $sortDirection = 'asc';

    #[On('autorExcluido')]
    public function placeholder() {}

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

    public function updatingBusca()
    {
        $this->resetPage();
    }

    public function render()
    {
        // CORREÇÃO: A busca agora começa filtrando pelo user_id do usuário logado
        $query = Autor::where('user_id', Auth::id());

        if (trim($this->busca)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->busca . '%')
                  ->orWhere('email', 'like', '%' . $this->busca . '%');
            });
        }

        $autores = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.autor-list', [
            'autores' => $autores,
        ]);
    }

      public function deleteAutor(Autor $autor)
    {
        $autor->delete();
        // Dispara um evento de sucesso para a notificação "toast"
        $this->dispatch('autorExcluido', 'Autor excluído com sucesso!');
    }
}
