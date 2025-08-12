<?php

namespace App\Console;

use App\Jobs\CleanupNotifications;
use App\Jobs\MonitorBudgetDeadlines;
use App\Jobs\MonitorViewedBudgets;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Monitorar prazos de orçamentos - executa a cada 30 minutos
        $schedule->job(new MonitorBudgetDeadlines())
            ->everyThirtyMinutes()
            ->name('monitor-budget-deadlines')
            ->description('Monitora prazos de orçamentos e cria notificações')
            ->withoutOverlapping(10) // Evita sobreposição por 10 minutos
            ->onOneServer() // Executa apenas em um servidor
            ->runInBackground();

        // Monitorar orçamentos visualizados - executa a cada 2 horas
        $schedule->job(new MonitorViewedBudgets())
            ->everyTwoHours()
            ->name('monitor-viewed-budgets')
            ->description('Monitora orçamentos visualizados sem ação')
            ->withoutOverlapping(30) // Evita sobreposição por 30 minutos
            ->onOneServer()
            ->runInBackground();

        // Limpeza de notificações - executa diariamente às 2:00
        $schedule->job(new CleanupNotifications())
            ->dailyAt('02:00')
            ->name('cleanup-notifications')
            ->description('Limpa notificações antigas e otimiza tabelas')
            ->withoutOverlapping(60) // Evita sobreposição por 1 hora
            ->onOneServer()
            ->runInBackground();

        // Backup de notificações importantes - executa semanalmente
        $schedule->call(function () {
            \Illuminate\Support\Facades\Log::info('Backup de notificações iniciado');
            
            // Aqui poderia ser implementado um backup das notificações importantes
            // Por exemplo, notificações de orçamentos de alto valor
            $importantNotifications = \App\Models\Notification::where('priority', 'high')
                ->where('created_at', '>=', now()->subWeek())
                ->count();
                
            \Illuminate\Support\Facades\Log::info('Backup de notificações concluído', [
                'important_notifications_count' => $importantNotifications
            ]);
        })
            ->weekly()
            ->sundays()
            ->at('03:00')
            ->name('backup-notifications')
            ->description('Backup semanal de notificações importantes');

        // Relatório de estatísticas de notificações - executa semanalmente
        $schedule->call(function () {
            $this->generateNotificationStats();
        })
            ->weekly()
            ->mondays()
            ->at('08:00')
            ->name('notification-stats')
            ->description('Gera relatório de estatísticas de notificações');

        // Verificação de saúde do sistema de notificações - executa a cada hora
        $schedule->call(function () {
            $this->checkNotificationSystemHealth();
        })
            ->hourly()
            ->name('notification-health-check')
            ->description('Verifica a saúde do sistema de notificações')
            ->withoutOverlapping(5);

        // Reset de snoozes expirados - executa a cada 15 minutos
        $schedule->call(function () {
            $resetCount = \App\Models\Notification::where('snooze_until', '<=', now())
                ->whereNotNull('snooze_until')
                ->update(['snooze_until' => null]);
                
            if ($resetCount > 0) {
                \Illuminate\Support\Facades\Log::info('Snoozes expirados resetados', [
                    'count' => $resetCount
                ]);
            }
        })
            ->everyFifteenMinutes()
            ->name('reset-expired-snoozes')
            ->description('Reseta snoozes de notificações expirados');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Generate notification statistics.
     */
    private function generateNotificationStats(): void
    {
        try {
            $stats = [
                'total_notifications' => \App\Models\Notification::count(),
            'unread_notifications' => \App\Models\Notification::where('is_read', false)->count(),
            'high_priority_notifications' => \App\Models\Notification::where('priority', 'high')->count(),
            'notifications_by_type' => \App\Models\Notification::selectRaw('type, COUNT(*) as count')
                    ->groupBy('type')
                    ->pluck('count', 'type')
                    ->toArray(),
                'notifications_last_week' => \App\Models\Notification::where('created_at', '>=', now()->subWeek())->count(),
            'most_active_users' => \App\Models\Notification::selectRaw('user_id, COUNT(*) as count')
                    ->where('created_at', '>=', now()->subWeek())
                    ->groupBy('user_id')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->pluck('count', 'user_id')
                    ->toArray(),
                'average_response_time' => \App\Models\Notification::whereNotNull('read_at')
                    ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, read_at)) as avg_minutes')
                    ->value('avg_minutes')
            ];

            \Illuminate\Support\Facades\Log::info('Relatório semanal de notificações', $stats);

            // Aqui poderia enviar o relatório por email para administradores
            // Mail::to('admin@sistema.com')->send(new NotificationStatsReport($stats));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao gerar estatísticas de notificações', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check notification system health.
     */
    private function checkNotificationSystemHealth(): void
    {
        try {
            $issues = [];

            // Verificar se há muitas notificações não lidas
            $unreadCount = \App\Models\Notification::where('is_read', false)->count();
            if ($unreadCount > 1000) {
                $issues[] = "Muitas notificações não lidas: {$unreadCount}";
            }

            // Verificar se há notificações muito antigas não lidas
            $oldUnreadCount = \App\Models\Notification::where('is_read', false)
                ->where('created_at', '<', now()->subDays(7))
                ->count();
            if ($oldUnreadCount > 100) {
                $issues[] = "Notificações não lidas muito antigas: {$oldUnreadCount}";
            }

            // Verificar se há usuários com configurações inválidas
            $invalidSettingsCount = \App\Models\NotificationSetting::whereJsonLength('enabled_types', 0)->count();
            if ($invalidSettingsCount > 0) {
                $issues[] = "Usuários com configurações inválidas: {$invalidSettingsCount}";
            }

            // Verificar se há ações órfãs
            $orphanActionsCount = \App\Models\NotificationAction::whereDoesntHave('notification')->count();
            if ($orphanActionsCount > 50) {
                $issues[] = "Ações órfãs detectadas: {$orphanActionsCount}";
            }

            if (!empty($issues)) {
                \Illuminate\Support\Facades\Log::warning('Problemas detectados no sistema de notificações', [
                    'issues' => $issues
                ]);
            } else {
                \Illuminate\Support\Facades\Log::debug('Sistema de notificações funcionando normalmente');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro na verificação de saúde do sistema de notificações', [
                'error' => $e->getMessage()
            ]);
        }
    }
}