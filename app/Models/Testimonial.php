<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_photo',
        'content',
        'rating',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'rating' => 'integer'
    ];

    /**
     * Scope para buscar apenas depoimentos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para ordenar por mais recentes
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Accessor para URL da foto do cliente
     */
    public function getClientPhotoUrlAttribute()
    {
        if ($this->client_photo) {
            return asset('storage/' . $this->client_photo);
        }
        return null;
    }

    /**
     * Validação para rating entre 1 e 5
     */
    public static function boot()
    {
        parent::boot();
        
        static::saving(function ($testimonial) {
            if ($testimonial->rating < 1 || $testimonial->rating > 5) {
                throw new \InvalidArgumentException('Rating deve estar entre 1 e 5');
            }
        });
    }
}