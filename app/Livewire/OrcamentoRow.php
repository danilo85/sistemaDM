<?php

namespace App\Livewire;

use App\Models\Orcamento;
use Livewire\Component;
use App\Events\BudgetApproved;
use App\Events\BudgetRejected;
use Illuminate\Support\Facades\Auth;

class OrcamentoRow extends Component
{
    public Orcamento $orcamento;

    // MÉTODO CORRIGIDO (a versão duplicada foi removida)
    public function mudarStatus(string $novoStatus)
        {
            $this->orcamento->status = $novoStatus;
            $this->orcamento->save();

            // Dispara eventos do novo sistema de notificações
            if ($novoStatus === 'Aprovado') {
                // Dispara o evento BudgetApproved para o novo sistema de notificações
                event(new BudgetApproved(
                    $this->orcamento,
                    Auth::user(), // Usuário que aprovou
                    'Orçamento aprovado via lista de orçamentos'
                ));
                
                // CORREÇÃO: Avisa o componente do sino que uma nova notificação foi enviada
                $this->dispatch('new-alert-sent');
            } elseif ($novoStatus === 'Rejeitado') {
                // Dispara o evento BudgetRejected para o novo sistema de notificações
                event(new BudgetRejected(
                    $this->orcamento,
                    Auth::user(), // Usuário que rejeitou
                    'Orçamento rejeitado via lista de orçamentos'
                ));
                
                // Avisa o componente do sino que uma nova notificação foi enviada
                $this->dispatch('new-alert-sent');
            }

            // Avisa a lista principal que o status foi atualizado
            $this->dispatch('status-atualizado');
        }
    public function excluirOrcamento()
    {
        $this->orcamento->delete();
        $this->dispatch('orcamento-excluido');
    }
    
    public function duplicar()
    {
        return redirect()->route('orcamentos.create', ['from' => $this->orcamento->id]);
    }
    
    public function render()
    {
        return view('livewire.orcamento-row');
    }
}