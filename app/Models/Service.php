<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'order_index',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'order_index' => 'integer'
    ];

    /**
     * Scope para buscar apenas serviços ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para ordenar por ordem de exibição
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }
}