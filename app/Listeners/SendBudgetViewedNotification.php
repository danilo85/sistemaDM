<?php

namespace App\Listeners;

use App\Events\BudgetViewed;
use App\Models\Notification;
use App\Models\NotificationSetting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendBudgetViewedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'notifications';
    public $delay = 10; // 10 segundos de delay para visualizações
    public $tries = 2;
    public $backoff = [60, 180]; // 1min, 3min

    /**
     * Handle the event.
     */
    public function handle(BudgetViewed $event): void
    {
        Log::debug('SendBudgetViewedNotification: Processando visualização de orçamento', [
            'budget_id' => $event->budget->id,
            'viewed_by' => $event->viewedBy->id,
            'is_first_view' => $event->isFirstView
        ]);

        try {
            // Só criar notificações se deve acionar notificações
            if ($event->shouldTriggerNotifications()) {
                $this->createNotifications($event);
            }
            
            $this->logViewEvent($event);
        } catch (\Exception $e) {
            Log::error('SendBudgetViewedNotification: Erro ao processar notificação', [
                'budget_id' => $event->budget->id,
                'viewed_by' => $event->viewedBy->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Create notifications for relevant users.
     */
    private function createNotifications(BudgetViewed $event): void
    {
        $usersToNotify = $event->getUsersToNotify();
        $notificationData = $event->getNotificationData();

        if (empty($usersToNotify)) {
            Log::debug('SendBudgetViewedNotification: Nenhum usuário para notificar', [
                'budget_id' => $event->budget->id,
                'viewed_by' => $event->viewedBy->id
            ]);
            return;
        }

        foreach ($usersToNotify as $userId) {
            $this->createUserNotification($userId, $event, $notificationData);
        }

        Log::info('SendBudgetViewedNotification: Notificações criadas', [
            'budget_id' => $event->budget->id,
            'users_notified' => count($usersToNotify),
            'user_ids' => $usersToNotify,
            'view_context' => $event->getViewContext()
        ]);
    }

    /**
     * Create notification for a specific user.
     */
    private function createUserNotification(int $userId, BudgetViewed $event, array $notificationData): void
    {
        // Verificar configurações do usuário
        $settings = NotificationSetting::getForUser($userId);
        
        if (!in_array('budget_viewed', $settings->enabled_types)) {
            Log::debug('SendBudgetViewedNotification: Tipo de notificação desabilitado para usuário', [
                'user_id' => $userId,
                'budget_id' => $event->budget->id
            ]);
            return;
        }

        // Para visualizações, verificar se já existe notificação recente (últimas 2 horas)
        $existingNotification = Notification::where('user_id', $userId)
            ->where('budget_id', $event->budget->id)
            ->where('type', 'budget_viewed')
            ->where('created_at', '>=', now()->subHours(2))
            ->first();

        if ($existingNotification) {
            Log::debug('SendBudgetViewedNotification: Notificação recente já existe', [
                'user_id' => $userId,
                'budget_id' => $event->budget->id,
                'existing_notification_id' => $existingNotification->id
            ]);
            return;
        }

        // Criar notificação com dados específicos de visualização
        $notification = Notification::create([
            'user_id' => $userId,
            'budget_id' => $event->budget->id,
            'type' => 'budget_viewed',
            'title' => $notificationData['title'],
            'message' => $notificationData['message'],
            'priority' => $notificationData['priority'],
            'metadata' => array_merge($notificationData['metadata'], [
                'listener_processed_at' => now()->toISOString(),
                'view_analytics' => $event->getAnalyticsData(),
                'view_summary' => $event->getViewSummary(),
                'view_context' => $event->getViewContext(),
                'view_pattern' => $event->getViewPattern(),
                'is_external_view' => $event->isExternalView(),
                'is_owner_view' => $event->isOwnerView()
            ])
        ]);

        Log::info('SendBudgetViewedNotification: Notificação criada para usuário', [
            'notification_id' => $notification->id,
            'user_id' => $userId,
            'budget_id' => $event->budget->id,
            'priority' => $notificationData['priority'],
            'view_context' => $event->getViewContext()
        ]);

        // Disparar evento de notificação criada
        event('notification.created', [
            'notification' => $notification,
            'user_id' => $userId,
            'type' => 'budget_viewed'
        ]);
    }

    /**
     * Log the view event for analytics.
     */
    private function logViewEvent(BudgetViewed $event): void
    {
        $summary = $event->getViewSummary();
        $analytics = $event->getAnalyticsData();
        
        Log::info('BudgetView: Orçamento visualizado', $summary);

        // Log detalhado para análise de padrões de visualização
        Log::info('BudgetView: Análise de visualização', [
            'budget_id' => $event->budget->id,
            'budget_value' => $event->budget->valor_total,
            'view_context' => $event->getViewContext(),
            'view_pattern' => $event->getViewPattern(),
            'analytics' => $analytics,
            'is_external_view' => $event->isExternalView(),
            'is_owner_view' => $event->isOwnerView(),
            'should_trigger_notifications' => $event->shouldTriggerNotifications()
        ]);

        // Log específico para primeira visualização
        if ($event->isFirstView) {
            Log::info('BudgetView: Primeira visualização do orçamento', [
                'budget_id' => $event->budget->id,
                'viewed_by' => $event->viewedBy->id,
                'days_since_creation' => $event->getDaysSinceCreation(),
                'client_id' => $event->budget->cliente_id
            ]);
        }

        // Log para visualizações frequentes
        if ($event->getViewPattern()['frequency'] === 'frequent') {
            Log::info('BudgetView: Visualização frequente detectada', [
                'budget_id' => $event->budget->id,
                'total_views' => $event->totalViews,
                'view_pattern' => $event->getViewPattern(),
                'viewed_by' => $event->viewedBy->id
            ]);
        }

        // Aqui poderia ser integrado com sistema de analytics
        // Analytics::track('budget_viewed', $summary);
    }

    /**
     * Handle a job failure.
     */
    public function failed(BudgetViewed $event, \Throwable $exception): void
    {
        Log::error('SendBudgetViewedNotification: Listener falhou', [
            'budget_id' => $event->budget->id,
            'viewed_by' => $event->viewedBy->id,
            'is_first_view' => $event->isFirstView,
            'total_views' => $event->totalViews,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        // Para visualizações críticas (primeira visualização de orçamentos de alto valor),
        // tentar notificação simplificada
        if ($event->isFirstView && $event->budget->valor_total >= 50000) {
            $this->sendSimpleNotification($event);
        }
    }

    /**
     * Send simple notification for critical views.
     */
    private function sendSimpleNotification(BudgetViewed $event): void
    {
        try {
            $usersToNotify = $event->getUsersToNotify();
            
            foreach ($usersToNotify as $userId) {
                Notification::create([
                    'user_id' => $userId,
                    'budget_id' => $event->budget->id,
                    'type' => 'budget_viewed',
                    'title' => 'Orçamento Visualizado',
                    'message' => "Orçamento #{$event->budget->id} foi visualizado pela primeira vez.",
                    'priority' => 'medium',
                    'metadata' => [
                        'simple_notification' => true,
                        'original_listener_failed' => true,
                        'is_first_view' => true,
                        'budget_value' => $event->budget->valor_total,
                        'viewed_at' => $event->viewedAt->toISOString()
                    ]
                ]);
            }
            
            Log::info('SendBudgetViewedNotification: Notificação simples enviada', [
                'budget_id' => $event->budget->id,
                'users_notified' => count($usersToNotify)
            ]);
        } catch (\Exception $e) {
            Log::error('SendBudgetViewedNotification: Falha na notificação simples', [
                'budget_id' => $event->budget->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Determine if the listener should be queued.
     */
    public function shouldQueue(BudgetViewed $event): bool
    {
        // Sempre enfileirar para não bloquear a resposta da visualização
        return true;
    }

    /**
     * Get the name of the queue the job should be sent to.
     */
    public function viaQueue(): string
    {
        return 'notifications';
    }

    /**
     * Get the number of seconds before the job should be processed.
     */
    public function withDelay(BudgetViewed $event): int
    {
        // Primeira visualização é processada mais rapidamente
        if ($event->isFirstView) {
            return 5; // 5 segundos
        }
        
        // Visualizações externas são processadas rapidamente
        if ($event->isExternalView()) {
            return 3; // 3 segundos
        }
        
        return 10; // 10 segundos padrão
    }
}