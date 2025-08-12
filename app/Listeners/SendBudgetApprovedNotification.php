<?php

namespace App\Listeners;

use App\Events\BudgetApproved;
use App\Models\Notification;
use App\Models\NotificationSetting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendBudgetApprovedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'notifications';
    public $delay = 5; // 5 segundos de delay
    public $tries = 3;
    public $backoff = [30, 60, 120]; // 30s, 1min, 2min

    /**
     * Handle the event.
     */
    public function handle(BudgetApproved $event): void
    {
        Log::info('SendBudgetApprovedNotification: Processando aprovação de orçamento', [
            'budget_id' => $event->budget->id,
            'approved_by' => $event->approvedBy->id
        ]);

        try {
            $this->createNotifications($event);
            $this->logApprovalEvent($event);
        } catch (\Exception $e) {
            Log::error('SendBudgetApprovedNotification: Erro ao processar notificação', [
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
    private function createNotifications(BudgetApproved $event): void
    {
        $usersToNotify = $event->getUsersToNotify();
        $notificationData = $event->getEventData()['notification_data'];

        foreach ($usersToNotify as $userId) {
            $this->createUserNotification($userId, $event, $notificationData);
        }

        Log::info('SendBudgetApprovedNotification: Notificações criadas', [
            'budget_id' => $event->budget->id,
            'users_notified' => count($usersToNotify),
            'user_ids' => $usersToNotify
        ]);
    }

    /**
     * Create notification for a specific user.
     */
    private function createUserNotification(int $userId, BudgetApproved $event, array $notificationData): void
    {
        // Verificar configurações do usuário
        $settings = NotificationSetting::getForUser($userId);
        
        if (!in_array('budget_approved', $settings->enabled_types)) {
            Log::debug('SendBudgetApprovedNotification: Tipo de notificação desabilitado para usuário', [
                'user_id' => $userId,
                'budget_id' => $event->budget->id
            ]);
            return;
        }

        // Verificar se já existe notificação recente para evitar duplicatas
        $existingNotification = Notification::where('user_id', $userId)
            ->where('budget_id', $event->budget->id)
            ->where('type', 'budget_approved')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();

        if ($existingNotification) {
            Log::debug('SendBudgetApprovedNotification: Notificação já existe', [
                'user_id' => $userId,
                'budget_id' => $event->budget->id,
                'existing_notification_id' => $existingNotification->id
            ]);
            return;
        }

        // Criar notificação
        $notification = Notification::create([
            'user_id' => $userId,
            'budget_id' => $event->budget->id,
            'type' => 'budget_approved',
            'title' => $notificationData['title'],
            'message' => $notificationData['message'],
            'priority' => $notificationData['priority'],
            'metadata' => array_merge($notificationData['metadata'], [
                'listener_processed_at' => now()->toISOString(),
                'approval_summary' => $event->getApprovalSummary()
            ])
        ]);

        Log::info('SendBudgetApprovedNotification: Notificação criada para usuário', [
            'notification_id' => $notification->id,
            'user_id' => $userId,
            'budget_id' => $event->budget->id,
            'priority' => $notificationData['priority']
        ]);

        // Disparar evento de notificação criada (para broadcasting, etc.)
        event('notification.created', [
            'notification' => $notification,
            'user_id' => $userId,
            'type' => 'budget_approved'
        ]);
    }

    /**
     * Log the approval event for analytics.
     */
    private function logApprovalEvent(BudgetApproved $event): void
    {
        $summary = $event->getApprovalSummary();
        
        Log::info('BudgetApproval: Orçamento aprovado', $summary);

        // Aqui poderia ser integrado com sistema de analytics
        // Analytics::track('budget_approved', $summary);
    }

    /**
     * Handle a job failure.
     */
    public function failed(BudgetApproved $event, \Throwable $exception): void
    {
        Log::error('SendBudgetApprovedNotification: Listener falhou', [
            'budget_id' => $event->budget->id,
            'approved_by' => $event->approvedBy->id,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        // Aqui poderia ser implementada uma notificação de fallback
        // ou um sistema de retry manual
    }

    /**
     * Determine if the listener should be queued.
     */
    public function shouldQueue(BudgetApproved $event): bool
    {
        // Sempre processar em queue para não bloquear a resposta
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
    public function withDelay(BudgetApproved $event): int
    {
        // Delay maior para orçamentos de alto valor para permitir possível cancelamento
        if ($event->isHighPriority()) {
            return 10; // 10 segundos
        }
        
        return 5; // 5 segundos padrão
    }
}