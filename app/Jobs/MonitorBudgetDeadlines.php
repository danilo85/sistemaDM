<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\Orcamento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MonitorBudgetDeadlines implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutos
    public $tries = 3;
    public $backoff = [60, 120, 300]; // 1min, 2min, 5min

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('MonitorBudgetDeadlines: Iniciando monitoramento de prazos');

        try {
            $this->checkApproachingDeadlines();
            $this->checkOverdueDeadlines();
            
            Log::info('MonitorBudgetDeadlines: Monitoramento concluído com sucesso');
        } catch (\Exception $e) {
            Log::error('MonitorBudgetDeadlines: Erro durante monitoramento', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Verificar orçamentos com prazos se aproximando
     */
    private function checkApproachingDeadlines(): void
    {
        // Buscar configurações de usuários ativos
        $settings = NotificationSetting::with('user')
            ->whereHas('user', function ($query) {
                $query->whereNotNull('email_verified_at');
            })
            ->get();

        foreach ($settings as $setting) {
            $this->checkUserDeadlines($setting);
        }
    }

    /**
     * Verificar prazos para um usuário específico
     */
    private function checkUserDeadlines(NotificationSetting $setting): void
    {
        $userId = $setting->user_id;
        $daysBeforeDeadline = $setting->days_before_deadline;
        
        // Data limite para notificação
        $deadlineThreshold = now()->addDays($daysBeforeDeadline);
        
        // Buscar orçamentos com prazo se aproximando
        $budgets = Orcamento::where(function ($query) use ($userId) {
                // Orçamentos do usuário ou que ele pode visualizar
                $query->where('user_id', $userId)
                      ->orWhere('created_by', $userId);
            })
            ->where('status', '!=', 'aprovado')
            ->where('status', '!=', 'rejeitado')
            ->where('status', '!=', 'cancelado')
            ->whereNotNull('prazo_entrega')
            ->where('prazo_entrega', '<=', $deadlineThreshold)
            ->where('prazo_entrega', '>', now())
            ->get();

        foreach ($budgets as $budget) {
            $this->createDeadlineNotification($budget, $setting);
        }
    }

    /**
     * Criar notificação de prazo se aproximando
     */
    private function createDeadlineNotification(Orcamento $budget, NotificationSetting $setting): void
    {
        // Verificar se já existe notificação recente para este orçamento
        $existingNotification = Notification::where('user_id', $setting->user_id)
            ->where('budget_id', $budget->id)
            ->where('type', 'deadline_approaching')
            ->where('created_at', '>=', now()->subHours(24))
            ->first();

        if ($existingNotification) {
            return; // Já existe notificação recente
        }

        $daysRemaining = now()->diffInDays($budget->prazo_entrega, false);
        $hoursRemaining = now()->diffInHours($budget->prazo_entrega, false);
        
        // Determinar prioridade baseada no tempo restante
        $priority = $this->calculateDeadlinePriority($daysRemaining, $hoursRemaining);
        
        // Criar mensagem personalizada
        $message = $this->createDeadlineMessage($budget, $daysRemaining, $hoursRemaining);
        
        // Verificar se o tipo está habilitado nas configurações
        if (!in_array('deadline_approaching', $setting->enabled_types)) {
            return;
        }

        Notification::create([
            'user_id' => $setting->user_id,
            'budget_id' => $budget->id,
            'type' => 'deadline_approaching',
            'title' => 'Prazo se aproximando',
            'message' => $message,
            'priority' => $priority,
            'metadata' => [
                'days_remaining' => $daysRemaining,
                'hours_remaining' => $hoursRemaining,
                'deadline_date' => $budget->prazo_entrega->toISOString(),
                'budget_value' => $budget->valor_total,
                'client_name' => $budget->cliente->nome ?? 'Cliente não informado',
                'auto_generated' => true,
                'notification_trigger' => 'deadline_monitor'
            ]
        ]);

        Log::info('MonitorBudgetDeadlines: Notificação criada', [
            'user_id' => $setting->user_id,
            'budget_id' => $budget->id,
            'days_remaining' => $daysRemaining,
            'priority' => $priority
        ]);
    }

    /**
     * Verificar orçamentos com prazo vencido
     */
    private function checkOverdueDeadlines(): void
    {
        $overdueBudgets = Orcamento::where('status', '!=', 'aprovado')
            ->where('status', '!=', 'rejeitado')
            ->where('status', '!=', 'cancelado')
            ->whereNotNull('prazo_entrega')
            ->where('prazo_entrega', '<', now())
            ->with(['user', 'cliente'])
            ->get();

        foreach ($overdueBudgets as $budget) {
            $this->createOverdueNotification($budget);
        }
    }

    /**
     * Criar notificação de prazo vencido
     */
    private function createOverdueNotification(Orcamento $budget): void
    {
        // Verificar se já existe notificação de vencimento recente
        $existingNotification = Notification::where('budget_id', $budget->id)
            ->where('type', 'deadline_overdue')
            ->where('created_at', '>=', now()->subDays(1))
            ->first();

        if ($existingNotification) {
            return;
        }

        $daysOverdue = now()->diffInDays($budget->prazo_entrega);
        
        // Notificar usuário responsável e criador
        $usersToNotify = collect([$budget->user_id, $budget->created_by])
            ->filter()
            ->unique();

        foreach ($usersToNotify as $userId) {
            Notification::create([
                'user_id' => $userId,
                'budget_id' => $budget->id,
                'type' => 'deadline_overdue',
                'title' => 'Prazo vencido',
                'message' => "O orçamento #{$budget->id} está {$daysOverdue} dia(s) em atraso. Cliente: {$budget->cliente->nome ?? 'N/A'}",
                'priority' => 'high',
                'metadata' => [
                    'days_overdue' => $daysOverdue,
                    'deadline_date' => $budget->prazo_entrega->toISOString(),
                    'budget_value' => $budget->valor_total,
                    'client_name' => $budget->cliente->nome ?? 'Cliente não informado',
                    'auto_generated' => true,
                    'notification_trigger' => 'overdue_monitor'
                ]
            ]);
        }

        Log::warning('MonitorBudgetDeadlines: Orçamento em atraso detectado', [
            'budget_id' => $budget->id,
            'days_overdue' => $daysOverdue,
            'users_notified' => $usersToNotify->toArray()
        ]);
    }

    /**
     * Calcular prioridade baseada no tempo restante
     */
    private function calculateDeadlinePriority(int $daysRemaining, int $hoursRemaining): string
    {
        if ($daysRemaining <= 0 && $hoursRemaining <= 24) {
            return 'high'; // Menos de 24 horas
        }
        
        if ($daysRemaining <= 1) {
            return 'high'; // 1 dia ou menos
        }
        
        if ($daysRemaining <= 3) {
            return 'medium'; // 2-3 dias
        }
        
        return 'low'; // Mais de 3 dias
    }

    /**
     * Criar mensagem personalizada para notificação de prazo
     */
    private function createDeadlineMessage(Orcamento $budget, int $daysRemaining, int $hoursRemaining): string
    {
        $clientName = $budget->cliente->nome ?? 'Cliente não informado';
        $budgetValue = 'R$ ' . number_format($budget->valor_total, 2, ',', '.');
        
        if ($daysRemaining <= 0) {
            if ($hoursRemaining <= 1) {
                return "⚠️ URGENTE: Orçamento #{$budget->id} vence em menos de 1 hora! Cliente: {$clientName} - Valor: {$budgetValue}";
            } else {
                return "⚠️ URGENTE: Orçamento #{$budget->id} vence em {$hoursRemaining} horas! Cliente: {$clientName} - Valor: {$budgetValue}";
            }
        } elseif ($daysRemaining == 1) {
            return "⏰ Orçamento #{$budget->id} vence amanhã! Cliente: {$clientName} - Valor: {$budgetValue}";
        } else {
            return "📅 Orçamento #{$budget->id} vence em {$daysRemaining} dias. Cliente: {$clientName} - Valor: {$budgetValue}";
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('MonitorBudgetDeadlines: Job falhou após todas as tentativas', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}