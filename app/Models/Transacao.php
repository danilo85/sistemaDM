<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transacao extends Model
{
    use HasFactory;

    protected $table = 'transacoes';

    protected $fillable = [
        'user_id',
        'descricao',
        'valor',
        'data',
        'tipo',
        'categoria_id',
        'pago',
        'status',
        'transacionavel_id',
        'transacionavel_type',
        'compra_id',
        'parcela_atual',
        'parcela_total',
        'is_recurring', 
        'frequency',    
        'next_due_date',
    ];

    protected $casts = [
        'data' => 'datetime',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function transacionavel()
    {
        return $this->morphTo();
    }
}