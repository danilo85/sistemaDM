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

    public function updateCor($clienteId, $cor)
    {
        $cliente = Cliente::find($clienteId);
        if ($cliente) {
            $cliente->update(['cor' => $cor]);
            $this->dispatch('corAtualizada', ['clienteId' => $clienteId, 'cor' => $cor]);
        }
    }

    public function render()
    {
        // CORREÇÃO TEMPORÁRIA: Removendo filtro por user_id para exibir todos os clientes
        $query = Cliente::query();

        // Aplica a busca se houver texto
        if (trim($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        $clientes = $query->orderBy($this->sortField, $this->sortDirection)->paginate(12);

        return view('livewire.cliente-list', [
            'clientes' => $clientes,
        ]);
    }
}
