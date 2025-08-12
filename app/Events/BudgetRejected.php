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

class BudgetRejected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Orcamento $budget;
    public User $rejectedBy;
    public ?string $rejectionReason;
    public \DateTime $rejectedAt;

    /**
     * Create a new event instance.
     */
    public function __construct(Orcamento $budget, User $rejectedBy, ?string $rejectionReason = null)
    {
        $this->budget = $budget;
        $this->rejectedBy = $rejectedBy;
        $this->rejectionReason = $rejectionReason;
        $this->rejectedAt = now();
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
            'rejected_by' => [
                'id' => $this->rejectedBy->id,
                'name' => $this->rejectedBy->name,
                'email' => $this->rejectedBy->email
            ],
            'rejection_reason' => $this->rejectionReason,
            'rejected_at' => $this->rejectedAt->toISOString(),
            'event_type' => 'budget_rejected'
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'budget.rejected';
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
            'rejected_by' => $this->rejectedBy,
            'rejection_reason' => $this->rejectionReason,
            'rejected_at' => $this->rejectedAt,
            'previous_status' => $this->budget->getOriginal('status'),
            'new_status' => $this->budget->status,
            'notification_data' => [
                'title' => 'Orçamento Rejeitado',
                'message' => $this->generateNotificationMessage(),
                'priority' => $this->calculateNotificationPriority(),
                'metadata' => [
                    'budget_id' => $this->budget->id,
                    'budget_value' => $this->budget->valor_total,
                    'client_name' => $this->budget->cliente->nome ?? 'Cliente não informado',
                    'rejected_by_name' => $this->rejectedBy->name,
                    'rejection_reason' => $this->rejectionReason,
                    'rejected_at' => $this->rejectedAt->toISOString(),
                    'event_trigger' => 'budget_rejection'
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
        $rejectorName = $this->rejectedBy->name;
        
        $message = "❌ Orçamento #{$this->budget->id} foi rejeitado por {$rejectorName}. ";
        $message .= "Cliente: {$clientName} - Valor: {$budgetValue}";
        
        if ($this->rejectionReason) {
            $message .= " - Motivo: {$this->rejectionReason}";
        }
        
        return $message;
    }

    /**
     * Calculate notification priority based on budget value and context.
     */
    private function calculateNotificationPriority(): string
    {
        // Rejeições sempre têm prioridade alta para orçamentos de alto valor
        if ($this->budget->valor_total >= 50000) {
            return 'high';
        }
        
        // Rejeições de orçamentos próximos ao prazo têm prioridade alta
        if ($this->budget->prazo_entrega && $this->budget->prazo_entrega <= now()->addDays(3)) {
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
        
        // Usuário responsável pelo orçamento
        if ($this->budget->user_id && $this->budget->user_id !== $this->rejectedBy->id) {
            $users->push($this->budget->user_id);
        }
        
        // Usuário que criou o orçamento
        if ($this->budget->created_by && 
            $this->budget->created_by !== $this->rejectedBy->id && 
            $this->budget->created_by !== $this->budget->user_id) {
            $users->push($this->budget->created_by);
        }
        
        // Remover duplicatas e retornar array
        return $users->unique()->filter()->values()->toArray();
    }

    /**
     * Check if this is a high-priority rejection.
     */
    public function isHighPriority(): bool
    {
        return $this->budget->valor_total >= 50000 || 
               ($this->budget->prazo_entrega && $this->budget->prazo_entrega <= now()->addDays(3)) ||
               $this->isRepeatedRejection();
    }

    /**
     * Check if this budget has been rejected before.
     */
    public function isRepeatedRejection(): bool
    {
        // Verificar se existe histórico de rejeições anteriores
        // Isso poderia ser implementado com um campo de histórico ou auditoria
        return false; // Placeholder - implementar conforme necessário
    }

    /**
     * Get rejection categories for analysis.
     */
    public function getRejectionCategory(): string
    {
        if (!$this->rejectionReason) {
            return 'no_reason';
        }
        
        $reason = strtolower($this->rejectionReason);
        
        if (str_contains($reason, 'preço') || str_contains($reason, 'valor') || str_contains($reason, 'caro')) {
            return 'price';
        }
        
        if (str_contains($reason, 'prazo') || str_contains($reason, 'tempo') || str_contains($reason, 'entrega')) {
            return 'deadline';
        }
        
        if (str_contains($reason, 'escopo') || str_contains($reason, 'requisito') || str_contains($reason, 'especificação')) {
            return 'scope';
        }
        
        if (str_contains($reason, 'orçamento') || str_contains($reason, 'verba') || str_contains($reason, 'recurso')) {
            return 'budget';
        }
        
        return 'other';
    }

    /**
     * Get suggested actions based on rejection.
     */
    public function getSuggestedActions(): array
    {
        $category = $this->getRejectionCategory();
        
        return match($category) {
            'price' => [
                'Revisar preços e custos',
                'Considerar alternativas mais econômicas',
                'Negociar condições de pagamento'
            ],
            'deadline' => [
                'Revisar cronograma do projeto',
                'Avaliar recursos adicionais',
                'Negociar novo prazo'
            ],
            'scope' => [
                'Esclarecer requisitos com o cliente',
                'Revisar escopo do projeto',
                'Agendar reunião de alinhamento'
            ],
            'budget' => [
                'Apresentar opções de parcelamento',
                'Propor fases do projeto',
                'Buscar alternativas de financiamento'
            ],
            default => [
                'Entrar em contato com o cliente',
                'Solicitar feedback detalhado',
                'Revisar proposta'
            ]
        };
    }

    /**
     * Get rejection summary for logging.
     */
    public function getRejectionSummary(): array
    {
        return [
            'budget_id' => $this->budget->id,
            'budget_value' => $this->budget->valor_total,
            'client_id' => $this->budget->cliente_id,
            'client_name' => $this->budget->cliente->nome ?? null,
            'rejected_by_id' => $this->rejectedBy->id,
            'rejected_by_name' => $this->rejectedBy->name,
            'rejection_reason' => $this->rejectionReason,
            'rejection_category' => $this->getRejectionCategory(),
            'rejected_at' => $this->rejectedAt->toISOString(),
            'previous_status' => $this->budget->getOriginal('status'),
            'new_status' => $this->budget->status,
            'is_high_priority' => $this->isHighPriority(),
            'is_repeated_rejection' => $this->isRepeatedRejection(),
            'users_to_notify' => $this->getUsersToNotify(),
            'suggested_actions' => $this->getSuggestedActions()
        ];
    }
}