<?php

namespace App\Livewire;

use App\Models\Cliente;
use Livewire\Component;

class ClienteRow extends Component
{
    public Cliente $cliente;

    public function deleteCliente()
    {
        $this->cliente->delete();
        // Avisa o componente "pai" (a lista) que um cliente foi excluído
        $this->dispatch('clienteExcluido');
    }

    public function render()
    {
        return view('livewire.cliente-row');
    }
}