<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'contact_person',
        'email',
        'phone',
        'is_complete',
    ];
    /**
     * Define o relacionamento: um Cliente TEM MUITOS Orçamentos.
     */
    public function orcamentos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Orcamento::class);
    }
    protected $casts = [
        'is_complete' => 'boolean', // <-- Diz ao Laravel para tratar este campo como booleano
    ];
}