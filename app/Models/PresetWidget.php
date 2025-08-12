<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresetWidget extends Model
{
    protected $fillable = [
        'preset_id',
        'widget_type',
        'title',
        'size',
        'position_x',
        'position_y',
        'width',
        'height',
        'is_pinned',
        'settings'
    ];

    protected $casts = [
        'settings' => 'array',
        'is_pinned' => 'boolean'
    ];

    public function preset(): BelongsTo
    {
        return $this->belongsTo(DashboardPreset::class, 'preset_id');
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

    // Ordenar por posição
    public function scopeOrderByPosition($query)
    {
        return $query->orderBy('position_y')->orderBy('position_x');
    }
}
