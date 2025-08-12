<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DashboardConfig extends Model
{
    protected $fillable = [
        'user_id',
        'layout',
        'widget_positions',
        'pinned_widgets',
        'is_default'
    ];

    protected $casts = [
        'layout' => 'array',
        'widget_positions' => 'array',
        'pinned_widgets' => 'array',
        'is_default' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function widgets(): HasMany
    {
        return $this->hasMany(DashboardWidget::class, 'user_id', 'user_id');
    }

    public function pinnedWidgets(): HasMany
    {
        return $this->widgets()->where('is_pinned', true)->orderBy('position_y');
    }
}
