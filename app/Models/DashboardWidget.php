<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DashboardWidget extends Model
{
    protected $fillable = [
        'user_id',
        'widget_type',
        'title',
        'size',
        'position_x',
        'position_y',
        'width',
        'height',
        'is_pinned',
        'settings',
        'is_active'
    ];

    protected $casts = [
        'settings' => 'array',
        'is_pinned' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dashboardConfig(): BelongsTo
    {
        return $this->belongsTo(DashboardConfig::class, 'user_id', 'user_id');
    }

    // Scope para widgets ativos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope para widgets fixados
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    // Scope por tipo de widget
    public function scopeOfType($query, $type)
    {
        return $query->where('widget_type', $type);
    }
}
