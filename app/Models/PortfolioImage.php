<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_id',
        'path',
        'is_thumb',
    ];

    protected $casts = [
        'is_thumb' => 'boolean',
    ];

    /**
     * Accessor para compatibilidade com views que usam image_path
     */
    public function getImagePathAttribute()
    {
        return $this->path;
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}