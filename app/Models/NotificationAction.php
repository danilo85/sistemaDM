<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationAction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'notification_id',
        'action_type',
        'action_data',
    ];

    protected $casts = [
        'action_data' => 'array',
        'created_at' => 'datetime',
    ];

    // Relacionamentos
    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }

    // Scopes
    public function scopeByType($query, string $type)
    {
        return $query->where('action_type', $type);
    }

    public function scopeForNotification($query, int $notificationId)
    {
        return $query->where('notification_id', $notificationId);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Métodos auxiliares
    public function getActionDescriptionAttribute(): string
    {
        return match($this->action_type) {
            'mark_read' => 'Marcada como lida',
            'snooze' => 'Adiada por ' . ($this->action_data['duration'] ?? 'tempo indefinido'),
            'open_budget' => 'Orçamento aberto',
            'send_reminder' => 'Lembrete enviado',
            default => 'Ação desconhecida'
        };
    }

    // Métodos estáticos para criação
    public static function logMarkRead(int $notificationId): self
    {
        return self::create([
            'notification_id' => $notificationId,
            'action_type' => 'mark_read',
            'action_data' => [
                'marked_at' => now(),
                'user_agent' => request()->userAgent() ?? null
            ]
        ]);
    }

    public static function logSnooze(int $notificationId, string $duration, \DateTime $snoozeUntil): self
    {
        return self::create([
            'notification_id' => $notificationId,
            'action_type' => 'snooze',
            'action_data' => [
                'duration' => $duration,
                'snooze_until' => $snoozeUntil,
                'snoozed_at' => now()
            ]
        ]);
    }

    public static function logOpenBudget(int $notificationId, int $budgetId): self
    {
        return self::create([
            'notification_id' => $notificationId,
            'action_type' => 'open_budget',
            'action_data' => [
                'budget_id' => $budgetId,
                'opened_at' => now(),
                'referrer' => request()->header('referer') ?? null
            ]
        ]);
    }

    public static function logSendReminder(int $notificationId, array $reminderData = []): self
    {
        return self::create([
            'notification_id' => $notificationId,
            'action_type' => 'send_reminder',
            'action_data' => array_merge([
                'sent_at' => now()
            ], $reminderData)
        ]);
    }

    // Métodos de estatística
    public static function getActionStats(int $userId, int $days = 30): array
    {
        $actions = self::whereHas('notification', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->recent($days)
        ->get()
        ->groupBy('action_type');

        return [
            'total_actions' => $actions->flatten()->count(),
            'mark_read' => $actions->get('mark_read', collect())->count(),
            'snooze' => $actions->get('snooze', collect())->count(),
            'open_budget' => $actions->get('open_budget', collect())->count(),
            'send_reminder' => $actions->get('send_reminder', collect())->count(),
        ];
    }

    public static function getMostCommonAction(int $userId, int $days = 30): ?string
    {
        $stats = self::getActionStats($userId, $days);
        unset($stats['total_actions']);
        
        if (empty($stats) || max($stats) === 0) {
            return null;
        }
        
        return array_search(max($stats), $stats);
    }

    // Validação de tipos de ação
    public static function getValidActionTypes(): array
    {
        return ['mark_read', 'snooze', 'open_budget', 'send_reminder'];
    }

    public static function isValidActionType(string $type): bool
    {
        return in_array($type, self::getValidActionTypes());
    }
}