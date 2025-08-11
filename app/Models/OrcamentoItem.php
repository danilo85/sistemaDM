<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrcamentoItem extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada ao model.
     * (Opcional, mas bom para clareza)
     */
    protected $table = 'orcamento_itens';

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'orcamento_id',
        'descricao',
        'quantidade',
        'valor_unitario',
        'subtotal',
    ];

    /**
     * Define o relacionamento: um Item PERTENCE A UM Orçamento.
     */
    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }
}