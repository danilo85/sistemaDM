<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'days_after_view',
        'days_before_deadline',
        'enabled_types',
        'priority_order',
    ];

    protected $casts = [
        'enabled_types' => 'array',
        'days_after_view' => 'integer',
        'days_before_deadline' => 'integer',
    ];

    // Relacionamentos
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Configurações padrão
    public static function getDefaults(): array
    {
        return [
            'days_after_view' => 3,
            'days_before_deadline' => 5,
            'enabled_types' => [
                'budget_approved',
                'budget_rejected',
                'deadline_near',
                'viewed_no_action'
            ],
            'priority_order' => 'high_first'
        ];
    }

    // Métodos auxiliares
    public function isTypeEnabled(string $type): bool
    {
        return in_array($type, $this->enabled_types ?? []);
    }

    public function getEnabledTypesAttribute($value): array
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? self::getDefaults()['enabled_types'];
        }
        
        return $value ?? self::getDefaults()['enabled_types'];
    }

    public function setEnabledTypesAttribute($value): void
    {
        $this->attributes['enabled_types'] = is_array($value) ? json_encode($value) : $value;
    }

    // Métodos estáticos
    public static function getForUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            self::getDefaults()
        );
    }

    public static function createDefaultForUser(int $userId): self
    {
        return self::create(array_merge(
            ['user_id' => $userId],
            self::getDefaults()
        ));
    }

    // Validação de tipos disponíveis
    public static function getAvailableTypes(): array
    {
        return [
            'budget_approved' => 'Orçamentos aprovados',
            'budget_rejected' => 'Orçamentos rejeitados',
            'deadline_near' => 'Prazos próximos',
            'viewed_no_action' => 'Visualizados sem ação'
        ];
    }

    public static function getAvailablePriorityOrders(): array
    {
        return [
            'high_first' => 'Alta primeiro',
            'low_first' => 'Baixa primeiro',
            'chronological' => 'Cronológica'
        ];
    }

    // Métodos de atualização
    public function updateSettings(array $settings): bool
    {
        $allowedFields = ['days_after_view', 'days_before_deadline', 'enabled_types', 'priority_order'];
        $filteredSettings = array_intersect_key($settings, array_flip($allowedFields));
        
        // Validar tipos habilitados
        if (isset($filteredSettings['enabled_types'])) {
            $availableTypes = array_keys(self::getAvailableTypes());
            $filteredSettings['enabled_types'] = array_intersect(
                $filteredSettings['enabled_types'],
                $availableTypes
            );
        }
        
        // Validar ordem de prioridade
        if (isset($filteredSettings['priority_order'])) {
            $availableOrders = array_keys(self::getAvailablePriorityOrders());
            if (!in_array($filteredSettings['priority_order'], $availableOrders)) {
                $filteredSettings['priority_order'] = 'high_first';
            }
        }
        
        // Validar valores numéricos
        if (isset($filteredSettings['days_after_view'])) {
            $filteredSettings['days_after_view'] = max(1, min(30, (int) $filteredSettings['days_after_view']));
        }
        
        if (isset($filteredSettings['days_before_deadline'])) {
            $filteredSettings['days_before_deadline'] = max(1, min(30, (int) $filteredSettings['days_before_deadline']));
        }
        
        return $this->update($filteredSettings);
    }

    // Métodos de consulta
    public function shouldCreateDeadlineNotification(\DateTime $deadline): bool
    {
        if (!$this->isTypeEnabled('deadline_near')) {
            return false;
        }
        
        $daysUntilDeadline = now()->diffInDays($deadline, false);
        return $daysUntilDeadline <= $this->days_before_deadline && $daysUntilDeadline >= 0;
    }

    public function shouldCreateViewedNotification(\DateTime $firstViewedAt): bool
    {
        if (!$this->isTypeEnabled('viewed_no_action')) {
            return false;
        }
        
        $daysSinceViewed = now()->diffInDays($firstViewedAt);
        return $daysSinceViewed >= $this->days_after_view;
    }
}