<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class WidgetCache extends Model
{
    protected $table = 'widget_cache';

    protected $fillable = [
        'cache_key',
        'widget_type',
        'user_id',
        'data',
        'expires_at'
    ];

    protected $casts = [
        'data' => 'array',
        'expires_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope para cache não expirado
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }

    // Scope para cache expirado
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    // Scope por tipo de widget
    public function scopeOfType($query, $type)
    {
        return $query->where('widget_type', $type);
    }

    // Verificar se o cache está válido
    public function isValid(): bool
    {
        return $this->expires_at > now();
    }

    // Verificar se o cache expirou
    public function isExpired(): bool
    {
        return $this->expires_at <= now();
    }

    // Limpar cache expirado
    public static function clearExpired(): int
    {
        return static::expired()->delete();
    }
}
