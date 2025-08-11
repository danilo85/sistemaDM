<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'orcamento_id',
        'portfolio_category_id',
        'title',
        'description',
        'post_date',
        'external_link',
        'link_legend',
        'is_active',
        'featured',
        'order_position',
    ];

    protected $casts = [
        'post_date' => 'date',
        'is_active' => 'boolean',
        'featured' => 'boolean',
        'order_position' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PortfolioCategory::class, 'portfolio_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PortfolioImage::class);
    }

    public function thumb()
    {
        return $this->hasOne(PortfolioImage::class)->where('is_thumb', true);
    }
}