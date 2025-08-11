<?php

namespace App\Livewire\Portfolio;

use App\Models\Orcamento;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Pipeline extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]
    public function render()
    {
        $userId = Auth::id();

        // Busca orçamentos que estão 'Finalizado' ou 'Pago'
        // E que ainda não têm um trabalho de portfólio associado
        $orcamentosConcluidos = Orcamento::where('user_id', $userId)
            ->whereIn('status', ['Finalizado', 'Pago'])
            ->whereDoesntHave('portfolio') // A mágica acontece aqui!
            ->latest()
            ->paginate(10);

        return view('livewire.portfolio.pipeline', [
            'orcamentos' => $orcamentosConcluidos,
        ]);
    }
}
