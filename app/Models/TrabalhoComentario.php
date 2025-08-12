<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrabalhoComentario extends Model
{
    use HasFactory;

    protected $table = 'trabalho_comentarios';

    protected $fillable = [
        'trabalho_id',
        'user_id',
        'comentario',
        'visivel_cliente',
        'importante',
    ];

    protected $casts = [
        'visivel_cliente' => 'boolean',
        'importante' => 'boolean',
    ];

    // Relacionamentos
    public function trabalho(): BelongsTo
    {
        return $this->belongsTo(Trabalho::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopeVisivelCliente($query)
    {
        return $query->where('visivel_cliente', true);
    }

    public function scopeImportante($query)
    {
        return $query->where('importante', true);
    }

    public function scopeInterno($query)
    {
        return $query->where('visivel_cliente', false);
    }

    // Métodos auxiliares
    public function getComentarioResumoAttribute(): string
    {
        return str_limit($this->comentario, 100);
    }

    public function getTempoDecorridoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getClasseCssAttribute(): string
    {
        $classes = [];
        
        if ($this->importante) {
            $classes[] = 'border-l-4 border-yellow-400 bg-yellow-50';
        }
        
        if ($this->visivel_cliente) {
            $classes[] = 'bg-blue-50';
        } else {
            $classes[] = 'bg-gray-50';
        }
        
        return implode(' ', $classes);
    }

    public function getIconeVisibilidadeAttribute(): string
    {
        return $this->visivel_cliente ? 'eye' : 'eye-slash';
    }

    public function getCorVisibilidadeAttribute(): string
    {
        return $this->visivel_cliente ? 'text-blue-600' : 'text-gray-400';
    }

    // Métodos de ação
    public function marcarComoImportante(): bool
    {
        $this->importante = true;
        return $this->save();
    }

    public function desmarcarImportante(): bool
    {
        $this->importante = false;
        return $this->save();
    }

    public function alternarVisibilidadeCliente(): bool
    {
        $this->visivel_cliente = !$this->visivel_cliente;
        return $this->save();
    }
}