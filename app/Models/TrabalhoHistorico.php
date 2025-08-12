<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrabalhoHistorico extends Model
{
    use HasFactory;

    protected $table = 'trabalho_historico';
    
    public $timestamps = false; // Apenas created_at

    protected $fillable = [
        'trabalho_id',
        'user_id',
        'status_anterior',
        'status_novo',
        'observacoes',
        'dados_adicionais',
    ];

    protected $casts = [
        'dados_adicionais' => 'array',
        'created_at' => 'datetime',
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

    // Métodos auxiliares
    public function getStatusAnteriorLabelAttribute(): ?string
    {
        if (!$this->status_anterior) {
            return null;
        }
        
        return Trabalho::getStatusOptions()[$this->status_anterior] ?? $this->status_anterior;
    }

    public function getStatusNovoLabelAttribute(): string
    {
        return Trabalho::getStatusOptions()[$this->status_novo] ?? $this->status_novo;
    }

    public function getDescricaoMovimentacaoAttribute(): string
    {
        if (!$this->status_anterior) {
            return "Trabalho criado com status '{$this->status_novo_label}'";
        }
        
        return "Status alterado de '{$this->status_anterior_label}' para '{$this->status_novo_label}'";
    }
}