<?php

namespace App\Livewire;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth; // Adicionar este import
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

class ClienteList extends Component
{
    use WithPagination;

    // Adicione este listener
    #[On('clienteExcluido')]
    public function placeholder()
    {
        // Este método vazio força o componente a se re-renderizar
    }

    // Conecta as propriedades com a URL
    #[Url(as: 'q')]
    public string $busca = '';

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

    public function updatingBusca()
    {
        $this->resetPage();
    }

    public function render()
    {
        // CORREÇÃO: A busca agora começa filtrando pelo user_id do usuário logado
        $query = Cliente::where('user_id', Auth::id());

        // Aplica a busca se houver texto
        if (trim($this->busca)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->busca . '%')
                  ->orWhere('email', 'like', '%' . $this->busca . '%');
            });
        }

        $clientes = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.cliente-list', [
            'clientes' => $clientes,
        ]);
    }
}
