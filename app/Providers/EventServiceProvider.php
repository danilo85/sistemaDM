<?php

namespace App\Providers;

use App\Events\BudgetApproved;
use App\Events\BudgetRejected;
use App\Events\BudgetViewed;
use App\Listeners\SendBudgetApprovedNotification;
use App\Listeners\SendBudgetRejectedNotification;
use App\Listeners\SendBudgetViewedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        // Budget Events
        BudgetApproved::class => [
            SendBudgetApprovedNotification::class,
        ],
        
        BudgetRejected::class => [
            SendBudgetRejectedNotification::class,
        ],
        
        BudgetViewed::class => [
            SendBudgetViewedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Registrar eventos customizados para notificações
        Event::listen('notification.created', function ($data) {
            \Illuminate\Support\Facades\Log::info('Notification created event triggered', [
                'notification_id' => $data['notification']->id ?? null,
                'user_id' => $data['user_id'] ?? null,
                'type' => $data['type'] ?? null
            ]);
        });

        // Evento para quando uma notificação é marcada como lida
        Event::listen('notification.read', function ($data) {
            \Illuminate\Support\Facades\Log::info('Notification marked as read', [
                'notification_id' => $data['notification_id'] ?? null,
                'user_id' => $data['user_id'] ?? null,
                'read_at' => $data['read_at'] ?? null
            ]);
        });

        // Evento para quando uma notificação é adiada
        Event::listen('notification.snoozed', function ($data) {
            \Illuminate\Support\Facades\Log::info('Notification snoozed', [
                'notification_id' => $data['notification_id'] ?? null,
                'user_id' => $data['user_id'] ?? null,
                'snooze_until' => $data['snooze_until'] ?? null
            ]);
        });

        // Evento para quando uma notificação é excluída
        Event::listen('notification.deleted', function ($data) {
            \Illuminate\Support\Facades\Log::info('Notification deleted', [
                'notification_id' => $data['notification_id'] ?? null,
                'user_id' => $data['user_id'] ?? null,
                'deleted_at' => $data['deleted_at'] ?? null
            ]);
        });

        // Evento para quando configurações de notificação são atualizadas
        Event::listen('notification.settings.updated', function ($data) {
            \Illuminate\Support\Facades\Log::info('Notification settings updated', [
                'user_id' => $data['user_id'] ?? null,
                'settings' => $data['settings'] ?? null
            ]);
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}