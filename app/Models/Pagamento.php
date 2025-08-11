<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pagamento extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'orcamento_id',
        'transacao_id',
        'valor_pago',
        'data_pagamento',
        'tipo_pagamento',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     */
    protected $casts = [
        'data_pagamento' => 'datetime',
    ];

    /**
     * Define o relacionamento: um Pagamento PERTENCE A UM Orçamento.
     */
    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }

    /**
     * Define o relacionamento: um Pagamento PERTENCE A UMA Transação.
     */
    public function transacao(): BelongsTo
    {
        return $this->belongsTo(Transacao::class);
    }
}