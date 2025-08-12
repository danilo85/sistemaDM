<?php

namespace App\Listeners;

use App\Events\BudgetRejected;
use App\Models\Notification;
use App\Models\NotificationSetting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendBudgetRejectedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'notifications';
    public $delay = 5; // 5 segundos de delay
    public $tries = 3;
    public $backoff = [30, 60, 120]; // 30s, 1min, 2min

    /**
     * Handle the event.
     */
    public function handle(BudgetRejected $event): void
    {
        Log::info('SendBudgetRejectedNotification: Processando rejeição de orçamento', [
            'budget_id' => $event->budget->id,
            'rejected_by' => $event->rejectedBy->id
        ]);

        try {
            $this->createNotifications($event);
            $this->logRejectionEvent($event);
        } catch (\Exception $e) {
            Log::error('SendBudgetRejectedNotification: Erro ao processar notificação', [
                'budget_id' => $event->budget->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Create notifications for relevant users.
     */
    private function createNotifications(BudgetRejected $event): void
    {
        $usersToNotify = $event->getUsersToNotify();
        $notificationData = $event->getEventData()['notification_data'];

        foreach ($usersToNotify as $userId) {
            $this->createUserNotification($userId, $event, $notificationData);
        }

        Log::info('SendBudgetRejectedNotification: Notificações criadas', [
            'budget_id' => $event->budget->id,
            'users_notified' => count($usersToNotify),
            'user_ids' => $usersToNotify
        ]);
    }

    /**
     * Create notification for a specific user.
     */
    private function createUserNotification(int $userId, BudgetRejected $event, array $notificationData): void
    {
        // Verificar configurações do usuário
        $settings = NotificationSetting::getForUser($userId);
        
        if (!in_array('budget_rejected', $settings->enabled_types)) {
            Log::debug('SendBudgetRejectedNotification: Tipo de notificação desabilitado para usuário', [
                'user_id' => $userId,
                'budget_id' => $event->budget->id
            ]);
            return;
        }

        // Verificar se já existe notificação recente para evitar duplicatas
        $existingNotification = DashboardNotification::where('user_id', $userId)
            ->where('budget_id', $event->budget->id)
            ->where('type', 'budget_rejected')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();

        if ($existingNotification) {
            Log::debug('SendBudgetRejectedNotification: Notificação já existe', [
                'user_id' => $userId,
                'budget_id' => $event->budget->id,
                'existing_notification_id' => $existingNotification->id
            ]);
            return;
        }

        // Criar notificação com dados adicionais para rejeições
        $notification = DashboardNotification::create([
            'user_id' => $userId,
            'budget_id' => $event->budget->id,
            'type' => 'budget_rejected',
            'title' => $notificationData['title'],
            'message' => $notificationData['message'],
            'priority' => $notificationData['priority'],
            'metadata' => array_merge($notificationData['metadata'], [
                'listener_processed_at' => now()->toISOString(),
                'rejection_summary' => $event->getRejectionSummary(),
                'rejection_category' => $event->getRejectionCategory(),
                'suggested_actions' => $event->getSuggestedActions(),
                'is_high_priority' => $event->isHighPriority()
            ])
        ]);

        Log::info('SendBudgetRejectedNotification: Notificação criada para usuário', [
            'notification_id' => $notification->id,
            'user_id' => $userId,
            'budget_id' => $event->budget->id,
            'priority' => $notificationData['priority'],
            'rejection_category' => $event->getRejectionCategory()
        ]);

        // Disparar evento de notificação criada
        event('notification.created', [
            'notification' => $notification,
            'user_id' => $userId,
            'type' => 'budget_rejected'
        ]);
    }

    /**
     * Log the rejection event for analytics.
     */
    private function logRejectionEvent(BudgetRejected $event): void
    {
        $summary = $event->getRejectionSummary();
        
        Log::info('BudgetRejection: Orçamento rejeitado', $summary);

        // Log adicional para análise de padrões de rejeição
        Log::info('BudgetRejection: Análise de rejeição', [
            'budget_id' => $event->budget->id,
            'budget_value' => $event->budget->valor_total,
            'rejection_category' => $event->getRejectionCategory(),
            'suggested_actions' => $event->getSuggestedActions(),
            'is_high_priority' => $event->isHighPriority(),
            'client_id' => $event->budget->cliente_id
        ]);

        // Aqui poderia ser integrado com sistema de analytics
        // Analytics::track('budget_rejected', $summary);
    }

    /**
     * Handle a job failure.
     */
    public function failed(BudgetRejected $event, \Throwable $exception): void
    {
        Log::error('SendBudgetRejectedNotification: Listener falhou', [
            'budget_id' => $event->budget->id,
            'rejected_by' => $event->rejectedBy->id,
            'rejection_reason' => $event->rejectionReason,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        // Para rejeições de alto valor, tentar notificação de emergência
        if ($event->isHighPriority()) {
            $this->sendEmergencyNotification($event);
        }
    }

    /**
     * Send emergency notification for high-priority rejections.
     */
    private function sendEmergencyNotification(BudgetRejected $event): void
    {
        try {
            // Criar notificação simples sem verificações complexas
            $usersToNotify = $event->getUsersToNotify();
            
            foreach ($usersToNotify as $userId) {
                Notification::create([
                    'user_id' => $userId,
                    'budget_id' => $event->budget->id,
                    'type' => 'budget_rejected',
                    'title' => 'Orçamento Rejeitado (Emergência)',
                    'message' => "Orçamento #{$event->budget->id} de alto valor foi rejeitado. Verifique imediatamente.",
                    'priority' => 'high',
                    'metadata' => [
                        'emergency_notification' => true,
                        'original_listener_failed' => true,
                        'budget_value' => $event->budget->valor_total,
                        'rejected_at' => $event->rejectedAt->toISOString()
                    ]
                ]);
            }
            
            Log::info('SendBudgetRejectedNotification: Notificação de emergência enviada', [
                'budget_id' => $event->budget->id,
                'users_notified' => count($usersToNotify)
            ]);
        } catch (\Exception $e) {
            Log::critical('SendBudgetRejectedNotification: Falha na notificação de emergência', [
                'budget_id' => $event->budget->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Determine if the listener should be queued.
     */
    public function shouldQueue(BudgetRejected $event): bool
    {
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
    public function withDelay(BudgetRejected $event): int
    {
        // Rejeições de alto valor são processadas mais rapidamente
        if ($event->isHighPriority()) {
            return 2; // 2 segundos
        }
        
        return 5; // 5 segundos padrão
    }
}