<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'orcamento_id',
        'portfolio_category_id',
        'title',
        'slug',
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

    /**
     * Boot method para gerar slug automaticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($portfolio) {
            if (empty($portfolio->slug)) {
                $portfolio->slug = static::generateUniqueSlug($portfolio->title);
            }
        });

        static::updating(function ($portfolio) {
            if ($portfolio->isDirty('title') && empty($portfolio->slug)) {
                $portfolio->slug = static::generateUniqueSlug($portfolio->title);
            }
        });
    }

    /**
     * Gerar slug único
     */
    private static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}