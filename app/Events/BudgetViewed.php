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
use Illuminate\Support\Facades\Request;

class BudgetViewed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Orcamento $budget;
    public User $viewedBy;
    public \DateTime $viewedAt;
    public ?string $userAgent;
    public ?string $ipAddress;
    public ?string $referrer;
    public bool $isFirstView;
    public int $totalViews;

    /**
     * Create a new event instance.
     */
    public function __construct(Orcamento $budget, User $viewedBy)
    {
        $this->budget = $budget;
        $this->viewedBy = $viewedBy;
        $this->viewedAt = now();
        $this->userAgent = Request::userAgent();
        $this->ipAddress = Request::ip();
        $this->referrer = Request::header('referer');
        
        // Determinar se é a primeira visualização
        $this->isFirstView = is_null($budget->first_viewed_at);
        
        // Incrementar contador de visualizações
        $this->totalViews = ($budget->view_count ?? 0) + 1;
    }

    /**
     * Get additional data for event listeners.
     */
    public function getEventData(): array
    {
        return [
            'budget' => $this->budget,
            'viewed_by' => $this->viewedBy,
            'viewed_at' => $this->viewedAt,
            'user_agent' => $this->userAgent,
            'ip_address' => $this->ipAddress,
            'referrer' => $this->referrer,
            'is_first_view' => $this->isFirstView,
            'total_views' => $this->totalViews,
            'view_context' => $this->getViewContext(),
            'tracking_data' => [
                'budget_id' => $this->budget->id,
                'budget_value' => $this->budget->valor_total,
                'budget_status' => $this->budget->status,
                'client_name' => $this->budget->cliente->nome ?? 'Cliente não informado',
                'viewed_by_name' => $this->viewedBy->name,
                'viewed_by_id' => $this->viewedBy->id,
                'is_owner' => $this->isOwnerView(),
                'is_creator' => $this->isCreatorView(),
                'days_since_creation' => $this->getDaysSinceCreation(),
                'event_trigger' => 'budget_view'
            ]
        ];
    }

    /**
     * Check if the viewer is the budget owner.
     */
    public function isOwnerView(): bool
    {
        return $this->budget->user_id === $this->viewedBy->id;
    }

    /**
     * Check if the viewer is the budget creator.
     */
    public function isCreatorView(): bool
    {
        return $this->budget->created_by === $this->viewedBy->id;
    }

    /**
     * Check if this is an external view (not owner or creator).
     */
    public function isExternalView(): bool
    {
        return !$this->isOwnerView() && !$this->isCreatorView();
    }

    /**
     * Get days since budget creation.
     */
    public function getDaysSinceCreation(): int
    {
        return $this->budget->created_at->diffInDays(now());
    }

    /**
     * Get days since first view.
     */
    public function getDaysSinceFirstView(): ?int
    {
        if (!$this->budget->first_viewed_at) {
            return null;
        }
        
        return $this->budget->first_viewed_at->diffInDays(now());
    }

    /**
     * Get view context information.
     */
    public function getViewContext(): array
    {
        return [
            'is_first_view' => $this->isFirstView,
            'is_owner_view' => $this->isOwnerView(),
            'is_creator_view' => $this->isCreatorView(),
            'is_external_view' => $this->isExternalView(),
            'view_frequency' => $this->getViewFrequency(),
            'time_between_views' => $this->getTimeBetweenViews(),
            'budget_age_days' => $this->getDaysSinceCreation(),
            'view_pattern' => $this->getViewPattern()
        ];
    }

    /**
     * Calculate view frequency (views per day).
     */
    public function getViewFrequency(): float
    {
        $daysSinceCreation = max(1, $this->getDaysSinceCreation());
        return round($this->totalViews / $daysSinceCreation, 2);
    }

    /**
     * Get time between this view and last view.
     */
    public function getTimeBetweenViews(): ?int
    {
        if (!$this->budget->last_viewed_at) {
            return null;
        }
        
        return $this->budget->last_viewed_at->diffInMinutes($this->viewedAt);
    }

    /**
     * Determine view pattern based on frequency and timing.
     */
    public function getViewPattern(): string
    {
        if ($this->isFirstView) {
            return 'first_view';
        }
        
        $frequency = $this->getViewFrequency();
        $timeBetween = $this->getTimeBetweenViews();
        
        if ($frequency >= 2) {
            return 'frequent'; // 2+ views per day
        }
        
        if ($timeBetween && $timeBetween <= 60) {
            return 'rapid'; // Multiple views within an hour
        }
        
        if ($frequency >= 0.5) {
            return 'regular'; // 1 view every 2 days
        }
        
        return 'occasional';
    }

    /**
     * Check if this view should trigger notifications.
     */
    public function shouldTriggerNotifications(): bool
    {
        // Não notificar para visualizações do próprio criador/responsável
        if ($this->isOwnerView() || $this->isCreatorView()) {
            return false;
        }
        
        // Notificar apenas para primeira visualização externa
        return $this->isFirstView && $this->isExternalView();
    }

    /**
     * Get notification data if notifications should be triggered.
     */
    public function getNotificationData(): ?array
    {
        if (!$this->shouldTriggerNotifications()) {
            return null;
        }
        
        return [
            'title' => 'Orçamento Visualizado',
            'message' => $this->generateNotificationMessage(),
            'priority' => $this->calculateNotificationPriority(),
            'metadata' => [
                'budget_id' => $this->budget->id,
                'budget_value' => $this->budget->valor_total,
                'client_name' => $this->budget->cliente->nome ?? 'Cliente não informado',
                'viewed_by_name' => $this->viewedBy->name,
                'viewed_by_id' => $this->viewedBy->id,
                'viewed_at' => $this->viewedAt->toISOString(),
                'is_first_view' => $this->isFirstView,
                'total_views' => $this->totalViews,
                'event_trigger' => 'budget_first_view'
            ]
        ];
    }

    /**
     * Generate notification message.
     */
    private function generateNotificationMessage(): string
    {
        $clientName = $this->budget->cliente->nome ?? 'Cliente não informado';
        $budgetValue = 'R$ ' . number_format($this->budget->valor_total, 2, ',', '.');
        $viewerName = $this->viewedBy->name;
        
        return "👁️ Orçamento #{$this->budget->id} foi visualizado por {$viewerName}. Cliente: {$clientName} - Valor: {$budgetValue}";
    }

    /**
     * Calculate notification priority.
     */
    private function calculateNotificationPriority(): string
    {
        // Primeira visualização de orçamentos de alto valor
        if ($this->budget->valor_total >= 50000) {
            return 'high';
        }
        
        // Primeira visualização próximo ao prazo
        if ($this->budget->prazo_entrega && $this->budget->prazo_entrega <= now()->addDays(7)) {
            return 'medium';
        }
        
        return 'low';
    }

    /**
     * Get users that should be notified.
     */
    public function getUsersToNotify(): array
    {
        if (!$this->shouldTriggerNotifications()) {
            return [];
        }
        
        $users = collect();
        
        // Notificar usuário responsável
        if ($this->budget->user_id && $this->budget->user_id !== $this->viewedBy->id) {
            $users->push($this->budget->user_id);
        }
        
        // Notificar criador
        if ($this->budget->created_by && 
            $this->budget->created_by !== $this->viewedBy->id && 
            $this->budget->created_by !== $this->budget->user_id) {
            $users->push($this->budget->created_by);
        }
        
        return $users->unique()->filter()->values()->toArray();
    }

    /**
     * Get view analytics data.
     */
    public function getAnalyticsData(): array
    {
        return [
            'budget_id' => $this->budget->id,
            'budget_value' => $this->budget->valor_total,
            'budget_status' => $this->budget->status,
            'client_id' => $this->budget->cliente_id,
            'viewed_by_id' => $this->viewedBy->id,
            'viewed_at' => $this->viewedAt->toISOString(),
            'is_first_view' => $this->isFirstView,
            'total_views' => $this->totalViews,
            'view_pattern' => $this->getViewPattern(),
            'view_frequency' => $this->getViewFrequency(),
            'time_between_views' => $this->getTimeBetweenViews(),
            'user_agent' => $this->userAgent,
            'ip_address' => $this->ipAddress,
            'referrer' => $this->referrer,
            'is_owner_view' => $this->isOwnerView(),
            'is_creator_view' => $this->isCreatorView(),
            'is_external_view' => $this->isExternalView()
        ];
    }

    /**
     * Get view summary for logging.
     */
    public function getViewSummary(): array
    {
        return [
            'budget_id' => $this->budget->id,
            'budget_value' => $this->budget->valor_total,
            'client_name' => $this->budget->cliente->nome ?? null,
            'viewed_by_id' => $this->viewedBy->id,
            'viewed_by_name' => $this->viewedBy->name,
            'viewed_at' => $this->viewedAt->toISOString(),
            'is_first_view' => $this->isFirstView,
            'total_views' => $this->totalViews,
            'view_pattern' => $this->getViewPattern(),
            'should_notify' => $this->shouldTriggerNotifications(),
            'users_to_notify' => $this->getUsersToNotify()
        ];
    }
}