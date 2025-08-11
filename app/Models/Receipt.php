<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'pagamento_id',
        'numero',
        'data_emissao',
        'valor',
        'token',
    ];

    /**
     * Define o relacionamento: um recibo pertence a um pagamento.
     */
    public function pagamento(): BelongsTo
    {
        return $this->belongsTo(Pagamento::class);
    }
}
