<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\NotificationAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanupNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600; // 10 minutos
    public $tries = 2;
    public $backoff = [300, 600]; // 5min, 10min

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('CleanupNotifications: Iniciando limpeza de notificações');

        try {
            $stats = [
                'old_notifications_deleted' => $this->cleanupOldNotifications(),
                'read_notifications_deleted' => $this->cleanupOldReadNotifications(),
                'orphaned_actions_deleted' => $this->cleanupOrphanedActions(),
                'expired_snoozes_reset' => $this->resetExpiredSnoozes(),
                'duplicate_notifications_removed' => $this->removeDuplicateNotifications()
            ];
            
            Log::info('CleanupNotifications: Limpeza concluída com sucesso', $stats);
        } catch (\Exception $e) {
            Log::error('CleanupNotifications: Erro durante limpeza', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Limpar notificações muito antigas (mais de 90 dias)
     */
    private function cleanupOldNotifications(): int
    {
        $cutoffDate = now()->subDays(90);
        
        $count = Notification::where('created_at', '<', $cutoffDate)
            ->count();
            
        if ($count > 0) {
            // Deletar ações relacionadas primeiro
            NotificationAction::whereHas('notification', function ($query) use ($cutoffDate) {
                $query->where('created_at', '<', $cutoffDate);
            })->delete();
            
            // Deletar notificações
            Notification::where('created_at', '<', $cutoffDate)
                ->delete();
                
            Log::info('CleanupNotifications: Notificações antigas removidas', [
                'count' => $count,
                'cutoff_date' => $cutoffDate->toDateString()
            ]);
        }
        
        return $count;
    }

    /**
     * Limpar notificações lidas antigas (mais de 30 dias)
     */
    private function cleanupOldReadNotifications(): int
    {
        $cutoffDate = now()->subDays(30);
        
        $count = Notification::where('is_read', true)
            ->where('created_at', '<', $cutoffDate)
            ->count();
            
        if ($count > 0) {
            // Deletar ações relacionadas primeiro
            NotificationAction::whereHas('notification', function ($query) use ($cutoffDate) {
                $query->where('is_read', true)
                      ->where('created_at', '<', $cutoffDate);
            })->delete();
            
            // Deletar notificações lidas antigas
            Notification::where('is_read', true)
                ->where('created_at', '<', $cutoffDate)
                ->delete();
                
            Log::info('CleanupNotifications: Notificações lidas antigas removidas', [
                'count' => $count,
                'cutoff_date' => $cutoffDate->toDateString()
            ]);
        }
        
        return $count;
    }

    /**
     * Limpar ações órfãs (sem notificação associada)
     */
    private function cleanupOrphanedActions(): int
    {
        $count = NotificationAction::whereDoesntHave('notification')->count();
        
        if ($count > 0) {
            NotificationAction::whereDoesntHave('notification')->delete();
            
            Log::info('CleanupNotifications: Ações órfãs removidas', [
                'count' => $count
            ]);
        }
        
        return $count;
    }

    /**
     * Resetar snoozes expirados
     */
    private function resetExpiredSnoozes(): int
    {
        $count = Notification::whereNotNull('snooze_until')
            ->where('snooze_until', '<=', now())
            ->count();
            
        if ($count > 0) {
            Notification::whereNotNull('snooze_until')
                ->where('snooze_until', '<=', now())
                ->update(['snooze_until' => null]);
                
            Log::info('CleanupNotifications: Snoozes expirados resetados', [
                'count' => $count
            ]);
        }
        
        return $count;
    }

    /**
     * Remover notificações duplicadas
     */
    private function removeDuplicateNotifications(): int
    {
        $duplicates = Notification::selectRaw('user_id, budget_id, type, COUNT(*) as count')
            ->groupBy('user_id', 'budget_id', 'type')
            ->having('count', '>', 1)
            ->where('created_at', '>=', now()->subDays(7)) // Apenas últimos 7 dias
            ->get();
            
        $removedCount = 0;
        
        foreach ($duplicates as $duplicate) {
            // Manter apenas a notificação mais recente
            $notifications = Notification::where('user_id', $duplicate->user_id)
                ->where('budget_id', $duplicate->budget_id)
                ->where('type', $duplicate->type)
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->get();
                
            // Remover todas exceto a primeira (mais recente)
            $toRemove = $notifications->skip(1);
            
            foreach ($toRemove as $notification) {
                // Deletar ações relacionadas
                $notification->actions()->delete();
                
                // Deletar notificação
                $notification->delete();
                $removedCount++;
            }
        }
        
        if ($removedCount > 0) {
            Log::info('CleanupNotifications: Notificações duplicadas removidas', [
                'count' => $removedCount
            ]);
        }
        
        return $removedCount;
    }

    /**
     * Limpar notificações de orçamentos que não existem mais
     */
    private function cleanupOrphanedBudgetNotifications(): int
    {
        $count = Notification::whereNotNull('budget_id')
            ->whereDoesntHave('budget')
            ->count();
            
        if ($count > 0) {
            // Deletar ações relacionadas primeiro
            NotificationAction::whereHas('notification', function ($query) {
                $query->whereNotNull('budget_id')
                      ->whereDoesntHave('budget');
            })->delete();
            
            // Deletar notificações órfãs
            Notification::whereNotNull('budget_id')
                ->whereDoesntHave('budget')
                ->delete();
                
            Log::info('CleanupNotifications: Notificações de orçamentos órfãos removidas', [
                'count' => $count
            ]);
        }
        
        return $count;
    }

    /**
     * Limpar notificações de usuários que não existem mais
     */
    private function cleanupOrphanedUserNotifications(): int
    {
        $count = Notification::whereDoesntHave('user')->count();
        
        if ($count > 0) {
            // Deletar ações relacionadas primeiro
            NotificationAction::whereHas('notification', function ($query) {
                $query->whereDoesntHave('user');
            })->delete();
            
            // Deletar notificações órfãs
            Notification::whereDoesntHave('user')->delete();
            
            Log::info('CleanupNotifications: Notificações de usuários órfãos removidas', [
                'count' => $count
            ]);
        }
        
        return $count;
    }

    /**
     * Otimizar tabelas após limpeza
     */
    private function optimizeTables(): void
    {
        try {
            // Otimizar tabelas no MySQL
            \DB::statement('OPTIMIZE TABLE dashboard_notifications');
            \DB::statement('OPTIMIZE TABLE notification_actions');
            
            Log::info('CleanupNotifications: Tabelas otimizadas');
        } catch (\Exception $e) {
            Log::warning('CleanupNotifications: Erro ao otimizar tabelas', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Gerar relatório de limpeza
     */
    private function generateCleanupReport(array $stats): void
    {
        $totalCleaned = array_sum($stats);
        
        if ($totalCleaned > 0) {
            Log::info('CleanupNotifications: Relatório de limpeza', [
                'total_items_cleaned' => $totalCleaned,
                'breakdown' => $stats,
                'cleanup_date' => now()->toDateTimeString()
            ]);
        }
    }

    /**
     * Verificar integridade dos dados
     */
    private function checkDataIntegrity(): array
    {
        $issues = [];
        
        // Verificar notificações sem usuário
        $orphanedUsers = Notification::whereDoesntHave('user')->count();
        if ($orphanedUsers > 0) {
            $issues[] = "Notificações sem usuário: {$orphanedUsers}";
        }
        
        // Verificar ações sem notificação
        $orphanedActions = NotificationAction::whereDoesntHave('notification')->count();
        if ($orphanedActions > 0) {
            $issues[] = "Ações sem notificação: {$orphanedActions}";
        }
        
        // Verificar snoozes expirados
        $expiredSnoozes = Notification::whereNotNull('snooze_until')
            ->where('snooze_until', '<=', now())
            ->count();
        if ($expiredSnoozes > 0) {
            $issues[] = "Snoozes expirados: {$expiredSnoozes}";
        }
        
        if (!empty($issues)) {
            Log::warning('CleanupNotifications: Problemas de integridade detectados', [
                'issues' => $issues
            ]);
        }
        
        return $issues;
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('CleanupNotifications: Job falhou após todas as tentativas', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}