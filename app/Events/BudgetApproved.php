<?php

namespace App\Events;

use App\Models\Orcamento;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BudgetApproved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Orcamento $budget;
    public User $approvedBy;
    public ?string $approvalNotes;
    public \DateTime $approvedAt;
    public bool $isPublicApproval = false;

    /**
     * Create a new event instance.
     */
    public function __construct(Orcamento $budget, User $approvedBy, ?string $approvalNotes = null, bool $isPublicApproval = false)
    {
        $this->budget = $budget;
        $this->approvedBy = $approvedBy;
        $this->approvalNotes = $approvalNotes;
        $this->approvedAt = now();
        $this->isPublicApproval = $isPublicApproval;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->budget->user_id),
            new PrivateChannel('user.' . $this->budget->created_by),
            new Channel('budget.' . $this->budget->id)
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'budget_id' => $this->budget->id,
            'budget_title' => $this->budget->titulo ?? "Orçamento #{$this->budget->id}",
            'budget_value' => $this->budget->valor_total,
            'client_name' => $this->budget->cliente->nome ?? 'Cliente não informado',
            'approved_by' => [
                'id' => $this->approvedBy->id,
                'name' => $this->approvedBy->name,
                'email' => $this->approvedBy->email
            ],
            'approval_notes' => $this->approvalNotes,
            'approved_at' => $this->approvedAt->toISOString(),
            'event_type' => 'budget_approved'
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'budget.approved';
    }

    /**
     * Determine if this event should broadcast.
     */
    public function shouldBroadcast(): bool
    {
        return true;
    }

    /**
     * Get additional data for event listeners.
     */
    public function getEventData(): array
    {
        return [
            'budget' => $this->budget,
            'approved_by' => $this->approvedBy,
            'approval_notes' => $this->approvalNotes,
            'approved_at' => $this->approvedAt,
            'previous_status' => $this->budget->getOriginal('status'),
            'new_status' => $this->budget->status,
            'notification_data' => [
                'title' => 'Orçamento Aprovado',
                'message' => $this->generateNotificationMessage(),
                'priority' => $this->calculateNotificationPriority(),
                'metadata' => [
                    'budget_id' => $this->budget->id,
                    'budget_value' => $this->budget->valor_total,
                    'client_name' => $this->budget->cliente->nome ?? 'Cliente não informado',
                    'approved_by_name' => $this->approvedBy->name,
                    'approval_notes' => $this->approvalNotes,
                    'approved_at' => $this->approvedAt->toISOString(),
                    'event_trigger' => 'budget_approval'
                ]
            ]
        ];
    }

    /**
     * Generate notification message based on budget data.
     */
    private function generateNotificationMessage(): string
    {
        $clientName = $this->budget->cliente->nome ?? 'Cliente não informado';
        $budgetValue = 'R$ ' . number_format($this->budget->valor_total, 2, ',', '.');
        $approverName = $this->approvedBy->name;
        
        $message = "✅ Orçamento #{$this->budget->id} foi aprovado por {$approverName}! ";
        $message .= "Cliente: {$clientName} - Valor: {$budgetValue}";
        
        if ($this->approvalNotes) {
            $message .= " - Observações: {$this->approvalNotes}";
        }
        
        return $message;
    }

    /**
     * Calculate notification priority based on budget value and urgency.
     */
    private function calculateNotificationPriority(): string
    {
        // Orçamentos de alto valor têm prioridade alta
        if ($this->budget->valor_total >= 50000) {
            return 'high';
        }
        
        // Orçamentos de valor médio têm prioridade média
        if ($this->budget->valor_total >= 10000) {
            return 'medium';
        }
        
        // Orçamentos de baixo valor têm prioridade baixa
        return 'low';
    }

    /**
     * Get users that should be notified.
     */
    public function getUsersToNotify(): array
    {
        $users = collect();
        
        // Para aprovação via link público, sempre notificar o usuário responsável
        if ($this->isPublicApproval) {
            if ($this->budget->user_id) {
                $users->push($this->budget->user_id);
            }
            if ($this->budget->created_by && $this->budget->created_by !== $this->budget->user_id) {
                $users->push($this->budget->created_by);
            }
        } else {
            // Para aprovação interna, manter lógica original (não notificar quem aprovou)
            if ($this->budget->user_id && $this->budget->user_id !== $this->approvedBy->id) {
                $users->push($this->budget->user_id);
            }
            
            if ($this->budget->created_by && 
                $this->budget->created_by !== $this->approvedBy->id && 
                $this->budget->created_by !== $this->budget->user_id) {
                $users->push($this->budget->created_by);
            }
        }
        
        // Remover duplicatas e retornar array
        return $users->unique()->filter()->values()->toArray();
    }

    /**
     * Check if this is a high-priority approval.
     */
    public function isHighPriority(): bool
    {
        return $this->budget->valor_total >= 50000 || 
               $this->budget->prazo_entrega <= now()->addDays(3);
    }

    /**
     * Get approval summary for logging.
     */
    public function getApprovalSummary(): array
    {
        return [
            'budget_id' => $this->budget->id,
            'budget_value' => $this->budget->valor_total,
            'client_id' => $this->budget->cliente_id,
            'client_name' => $this->budget->cliente->nome ?? null,
            'approved_by_id' => $this->approvedBy->id,
            'approved_by_name' => $this->approvedBy->name,
            'approval_notes' => $this->approvalNotes,
            'approved_at' => $this->approvedAt->toISOString(),
            'previous_status' => $this->budget->getOriginal('status'),
            'new_status' => $this->budget->status,
            'is_high_priority' => $this->isHighPriority(),
            'users_to_notify' => $this->getUsersToNotify()
        ];
    }
}