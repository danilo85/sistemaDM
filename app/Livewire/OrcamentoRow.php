<?php

namespace App\Livewire;

use App\Models\Orcamento;
use Livewire\Component;
use App\Notifications\OrcamentoAprovadoNotification;

class OrcamentoRow extends Component
{
    public Orcamento $orcamento;

    // MÉTODO CORRIGIDO (a versão duplicada foi removida)
    public function mudarStatus(string $novoStatus)
        {
            $this->orcamento->status = $novoStatus;
            $this->orcamento->save();

            // Dispara a notificação quando o status é "Aprovado"
            if ($novoStatus === 'Aprovado') {
                // Envia a notificação para o dono do orçamento
                $this->orcamento->user->notify(new OrcamentoAprovadoNotification($this->orcamento));
                
                // CORREÇÃO: Avisa o componente do sino que uma nova notificação foi enviada
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