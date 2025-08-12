<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'budget_id',
        'type',
        'title',
        'message',
        'priority',
        'is_read',
        'snooze_until',
        'metadata',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'snooze_until' => 'datetime',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relacionamentos
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class, 'budget_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(NotificationAction::class, 'notification_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeNotSnoozed($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('snooze_until')
              ->orWhere('snooze_until', '<=', now());
        });
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrderByPriority($query, $priorityOrder = 'deadlines_first')
    {
        if ($priorityOrder === 'deadlines_first') {
            return $query->orderByRaw("CASE 
                WHEN type = 'deadline_near' THEN 1
                WHEN priority = 'high' THEN 2
                WHEN priority = 'medium' THEN 3
                ELSE 4
            END")->orderBy('created_at', 'desc');
        }
        
        return $query->orderByRaw("CASE 
            WHEN type IN ('budget_approved', 'budget_rejected') THEN 1
            WHEN priority = 'high' THEN 2
            WHEN priority = 'medium' THEN 3
            ELSE 4
        END")->orderBy('created_at', 'desc');
    }

    // Métodos auxiliares
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
        
        // Registrar ação
        $this->actions()->create([
            'action_type' => 'mark_read',
            'action_data' => ['marked_at' => now()]
        ]);
    }

    public function snooze(string $duration): void
    {
        $snoozeUntil = match($duration) {
            '24h' => now()->addHours(24),
            '48h' => now()->addHours(48),
            '1w' => now()->addWeek(),
            default => now()->addHours(24)
        };

        $this->update(['snooze_until' => $snoozeUntil]);
        
        // Registrar ação
        $this->actions()->create([
            'action_type' => 'snooze',
            'action_data' => [
                'duration' => $duration,
                'snooze_until' => $snoozeUntil
            ]
        ]);
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getIconAttribute(): string
    {
        return match($this->type) {
            'budget_approved' => '✅',
            'budget_rejected' => '❌',
            'deadline_near' => '⏳',
            'viewed_no_action' => '👁️',
            default => '📢'
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'high' => 'text-red-600 bg-red-50',
            'medium' => 'text-yellow-600 bg-yellow-50',
            'low' => 'text-blue-600 bg-blue-50',
            default => 'text-gray-600 bg-gray-50'
        };
    }

    // Métodos estáticos para criação
    public static function createBudgetApproved(Orcamento $budget): self
    {
        return self::create([
            'user_id' => $budget->user_id,
            'budget_id' => $budget->id,
            'type' => 'budget_approved',
            'title' => 'Orçamento aprovado',
            'message' => "✅ Aprovado: {$budget->titulo} – Cliente {$budget->cliente->nome}",
            'priority' => 'high',
            'metadata' => [
                'budget_title' => $budget->titulo,
                'client_name' => $budget->cliente->nome,
                'approved_at' => now()
            ]
        ]);
    }

    public static function createBudgetRejected(Orcamento $budget): self
    {
        return self::create([
            'user_id' => $budget->user_id,
            'budget_id' => $budget->id,
            'type' => 'budget_rejected',
            'title' => 'Orçamento rejeitado',
            'message' => "❌ Rejeitado: {$budget->titulo} – Cliente {$budget->cliente->nome}",
            'priority' => 'high',
            'metadata' => [
                'budget_title' => $budget->titulo,
                'client_name' => $budget->cliente->nome,
                'rejected_at' => now()
            ]
        ]);
    }

    public static function createDeadlineNear(Orcamento $budget, int $daysLeft): self
    {
        return self::create([
            'user_id' => $budget->user_id,
            'budget_id' => $budget->id,
            'type' => 'deadline_near',
            'title' => 'Prazo próximo',
            'message' => "⏳ Prazo em {$daysLeft} dias: Orçamento #{$budget->id} – Cliente {$budget->cliente->nome}",
            'priority' => 'high',
            'metadata' => [
                'budget_title' => $budget->titulo,
                'client_name' => $budget->cliente->nome,
                'days_left' => $daysLeft,
                'deadline' => $budget->deadline
            ]
        ]);
    }

    public static function createViewedNoAction(Orcamento $budget, int $daysAgo): self
    {
        return self::create([
            'user_id' => $budget->user_id,
            'budget_id' => $budget->id,
            'type' => 'viewed_no_action',
            'title' => 'Visualizado sem ação',
            'message' => "👁️ Visualizado há {$daysAgo} dias e ainda não aprovado – Orçamento #{$budget->id}",
            'priority' => 'medium',
            'metadata' => [
                'budget_title' => $budget->titulo,
                'client_name' => $budget->cliente->nome,
                'days_ago' => $daysAgo,
                'first_viewed_at' => $budget->first_viewed_at
            ]
        ]);
    }
}