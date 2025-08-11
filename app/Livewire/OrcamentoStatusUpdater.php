<?php

namespace App\Livewire;

use App\Models\Orcamento;
use Livewire\Component;
use Livewire\Attributes\On;

class OrcamentoStatusUpdater extends Component
{
    // A propriedade pública para receber o orçamento da página principal
    public Orcamento $orcamento;
    // Ouve o evento e atualiza-se se o ID corresponder
        protected $listeners = ['status-atualizado' => 'checkForUpdates'];

        public function mount(Orcamento $orcamento)
        {
            $this->orcamento = $orcamento;
        }

        public function checkForUpdates($orcamentoId)
        {
            if ($this->orcamento->id === $orcamentoId) {
                $this->orcamento->refresh();
            }
        }
    #[On('status-atualizado')]
        public function refreshOrcamento()
        {
            // 3. Este comando força o model a recarregar seus dados do banco
            $this->orcamento->refresh();
        }
    /**
     * Método chamado pelos botões do dropdown para mudar o status.
     */
    public function mudarStatus(string $novoStatus)
    {
        // Validação para garantir que o status enviado é um dos permitidos
        $statusPermitidos = ['Analisando', 'Aprovado', 'Rejeitado', 'Finalizado', 'Pago'];
        if (!in_array($novoStatus, $statusPermitidos)) {
            return; // Se não for um status válido, não faz nada
        }

        // Atualiza o status do orçamento
        $this->orcamento->status = $novoStatus;
        $this->orcamento->save();

        // Dispara um evento para mostrar a notificação "toast"
        $this->dispatch('orcamento-atualizado', 'Status atualizado para ' . $novoStatus . '!');
    }

    public function render()
    {
        return view('livewire.orcamento-status-updater');
    }
}