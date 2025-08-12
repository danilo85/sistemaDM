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

class MonitorViewedBudgets implements ShouldQueue
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
        Log::info('MonitorViewedBudgets: Iniciando monitoramento de orçamentos visualizados');

        try {
            $this->checkViewedBudgetsWithoutAction();
            $this->checkFrequentlyViewedBudgets();
            
            Log::info('MonitorViewedBudgets: Monitoramento concluído com sucesso');
        } catch (\Exception $e) {
            Log::error('MonitorViewedBudgets: Erro durante monitoramento', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Verificar orçamentos visualizados sem ação
     */
    private function checkViewedBudgetsWithoutAction(): void
    {
        // Buscar configurações de usuários ativos
        $settings = NotificationSetting::with('user')
            ->whereHas('user', function ($query) {
                $query->whereNotNull('email_verified_at');
            })
            ->get();

        foreach ($settings as $setting) {
            $this->checkUserViewedBudgets($setting);
        }
    }

    /**
     * Verificar orçamentos visualizados para um usuário específico
     */
    private function checkUserViewedBudgets(NotificationSetting $setting): void
    {
        $userId = $setting->user_id;
        $daysAfterView = $setting->days_after_view;
        
        // Data limite para notificação (orçamentos visualizados há X dias)
        $viewThreshold = now()->subDays($daysAfterView);
        
        // Buscar orçamentos visualizados sem ação
        $budgets = Orcamento::where(function ($query) use ($userId) {
                // Orçamentos do usuário ou que ele pode visualizar
                $query->where('user_id', $userId)
                      ->orWhere('created_by', $userId);
            })
            ->where('status', 'pendente') // Apenas orçamentos pendentes
            ->whereNotNull('first_viewed_at')
            ->where('first_viewed_at', '<=', $viewThreshold)
            ->where(function ($query) {
                // Sem aprovação ou rejeição recente
                $query->whereNull('approved_at')
                      ->whereNull('rejected_at');
            })
            ->with(['cliente', 'user'])
            ->get();

        foreach ($budgets as $budget) {
            $this->createViewedWithoutActionNotification($budget, $setting);
        }
    }

    /**
     * Criar notificação para orçamento visualizado sem ação
     */
    private function createViewedWithoutActionNotification(Orcamento $budget, NotificationSetting $setting): void
    {
        // Verificar se já existe notificação recente para este orçamento
        $existingNotification = Notification::where('user_id', $setting->user_id)
            ->where('budget_id', $budget->id)
            ->where('type', 'viewed_no_action')
            ->where('created_at', '>=', now()->subDays(3)) // Evitar spam
            ->first();

        if ($existingNotification) {
            return; // Já existe notificação recente
        }

        // Verificar se o tipo está habilitado nas configurações
        if (!in_array('viewed_no_action', $setting->enabled_types)) {
            return;
        }

        $daysSinceViewed = now()->diffInDays($budget->first_viewed_at);
        $viewCount = $budget->view_count ?? 1;
        
        // Determinar prioridade baseada no tempo e número de visualizações
        $priority = $this->calculateViewedPriority($daysSinceViewed, $viewCount, $budget->valor_total);
        
        // Criar mensagem personalizada
        $message = $this->createViewedMessage($budget, $daysSinceViewed, $viewCount);

        DashboardNotification::create([
            'user_id' => $setting->user_id,
            'budget_id' => $budget->id,
            'type' => 'viewed_no_action',
            'title' => 'Orçamento aguardando ação',
            'message' => $message,
            'priority' => $priority,
            'metadata' => [
                'days_since_viewed' => $daysSinceViewed,
                'view_count' => $viewCount,
                'first_viewed_at' => $budget->first_viewed_at->toISOString(),
                'last_viewed_at' => $budget->last_viewed_at?->toISOString(),
                'budget_value' => $budget->valor_total,
                'client_name' => $budget->cliente->nome ?? 'Cliente não informado',
                'auto_generated' => true,
                'notification_trigger' => 'viewed_monitor'
            ]
        ]);

        Log::info('MonitorViewedBudgets: Notificação de orçamento sem ação criada', [
            'user_id' => $setting->user_id,
            'budget_id' => $budget->id,
            'days_since_viewed' => $daysSinceViewed,
            'view_count' => $viewCount,
            'priority' => $priority
        ]);
    }

    /**
     * Verificar orçamentos visualizados com muita frequência
     */
    private function checkFrequentlyViewedBudgets(): void
    {
        // Buscar orçamentos com muitas visualizações mas sem ação
        $frequentlyViewed = Orcamento::where('status', 'pendente')
            ->where('view_count', '>=', 10) // 10 ou mais visualizações
            ->whereNotNull('first_viewed_at')
            ->where('first_viewed_at', '<=', now()->subDays(2)) // Pelo menos 2 dias desde primeira visualização
            ->whereNull('approved_at')
            ->whereNull('rejected_at')
            ->with(['cliente', 'user'])
            ->get();

        foreach ($frequentlyViewed as $budget) {
            $this->createFrequentlyViewedNotification($budget);
        }
    }

    /**
     * Criar notificação para orçamento muito visualizado
     */
    private function createFrequentlyViewedNotification(Orcamento $budget): void
    {
        // Verificar se já existe notificação recente
        $existingNotification = DashboardNotification::where('budget_id', $budget->id)
            ->where('type', 'frequently_viewed')
            ->where('created_at', '>=', now()->subWeek())
            ->first();

        if ($existingNotification) {
            return;
        }

        // Notificar usuário responsável e criador
        $usersToNotify = collect([$budget->user_id, $budget->created_by])
            ->filter()
            ->unique();

        foreach ($usersToNotify as $userId) {
            // Verificar configurações do usuário
            $setting = NotificationSetting::getForUser($userId);
            if (!in_array('frequently_viewed', $setting->enabled_types ?? [])) {
                continue;
            }

            Notification::create([
                'user_id' => $userId,
                'budget_id' => $budget->id,
                'type' => 'frequently_viewed',
                'title' => 'Orçamento muito visualizado',
                'message' => $this->createFrequentlyViewedMessage($budget),
                'priority' => 'medium',
                'metadata' => [
                    'view_count' => $budget->view_count,
                    'days_since_first_view' => now()->diffInDays($budget->first_viewed_at),
                    'first_viewed_at' => $budget->first_viewed_at->toISOString(),
                    'last_viewed_at' => $budget->last_viewed_at?->toISOString(),
                    'budget_value' => $budget->valor_total,
                    'client_name' => $budget->cliente->nome ?? 'Cliente não informado',
                    'auto_generated' => true,
                    'notification_trigger' => 'frequent_view_monitor'
                ]
            ]);
        }

        Log::info('MonitorViewedBudgets: Notificação de orçamento muito visualizado criada', [
            'budget_id' => $budget->id,
            'view_count' => $budget->view_count,
            'users_notified' => $usersToNotify->toArray()
        ]);
    }

    /**
     * Calcular prioridade baseada no tempo e visualizações
     */
    private function calculateViewedPriority(int $daysSinceViewed, int $viewCount, float $budgetValue): string
    {
        // Orçamentos de alto valor têm prioridade maior
        $isHighValue = $budgetValue >= 10000;
        
        // Muitas visualizações indicam interesse
        $isFrequentlyViewed = $viewCount >= 5;
        
        // Muito tempo sem ação
        $isOld = $daysSinceViewed >= 7;
        
        if ($isHighValue && ($isFrequentlyViewed || $isOld)) {
            return 'high';
        }
        
        if ($isFrequentlyViewed || $daysSinceViewed >= 5) {
            return 'medium';
        }
        
        return 'low';
    }

    /**
     * Criar mensagem para orçamento visualizado sem ação
     */
    private function createViewedMessage(Orcamento $budget, int $daysSinceViewed, int $viewCount): string
    {
        $clientName = $budget->cliente->nome ?? 'Cliente não informado';
        $budgetValue = 'R$ ' . number_format($budget->valor_total, 2, ',', '.');
        
        $viewText = $viewCount > 1 ? "{$viewCount} visualizações" : "1 visualização";
        
        if ($daysSinceViewed >= 7) {
            return "📋 Orçamento #{$budget->id} foi visualizado há {$daysSinceViewed} dias ({$viewText}) mas ainda não foi aprovado/rejeitado. Cliente: {$clientName} - Valor: {$budgetValue}";
        } else {
            return "👀 Orçamento #{$budget->id} com {$viewText} há {$daysSinceViewed} dias aguarda decisão. Cliente: {$clientName} - Valor: {$budgetValue}";
        }
    }

    /**
     * Criar mensagem para orçamento muito visualizado
     */
    private function createFrequentlyViewedMessage(Orcamento $budget): string
    {
        $clientName = $budget->cliente->nome ?? 'Cliente não informado';
        $budgetValue = 'R$ ' . number_format($budget->valor_total, 2, ',', '.');
        $daysSinceFirst = now()->diffInDays($budget->first_viewed_at);
        
        return "🔍 Orçamento #{$budget->id} foi visualizado {$budget->view_count} vezes em {$daysSinceFirst} dias, mas ainda não foi decidido. Pode precisar de atenção especial. Cliente: {$clientName} - Valor: {$budgetValue}";
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('MonitorViewedBudgets: Job falhou após todas as tentativas', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}