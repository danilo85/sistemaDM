<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DashboardPreset extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'layout_config',
        'icon',
        'is_system',
        'is_active'
    ];

    protected $casts = [
        'layout_config' => 'array',
        'is_system' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function widgets(): HasMany
    {
        return $this->hasMany(PresetWidget::class, 'preset_id');
    }

    // Scope para presets ativos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope para presets do sistema
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    // Scope para presets de usuário
    public function scopeUser($query)
    {
        return $query->where('is_system', false);
    }

    // Buscar preset por slug
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }
}
