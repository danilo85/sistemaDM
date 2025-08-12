<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'token',
        'extrato_ativo',
    ];
    /**
     * Define o relacionamento: um Cliente PERTENCE A um Usuário.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define o relacionamento: um Cliente TEM MUITOS Orçamentos.
     */
    public function orcamentos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Orcamento::class);
    }
    protected $casts = [
        'is_complete' => 'boolean', // <-- Diz ao Laravel para tratar este campo como booleano
        'extrato_ativo' => 'boolean',
    ];

    /**
     * O método "boot" do model.
     */
    protected static function boot()
    {
        parent::boot();

        // Evento que é disparado ANTES de um novo cliente ser criado
        static::creating(function ($cliente) {
            // Gera um UUID (token) único e o atribui ao novo cliente
            if (empty($cliente->token)) {
                $cliente->token = Str::uuid();
            }
        });
    }

    /**
     * Retorna a URL pública e segura para o extrato deste cliente.
     */
    public function getPublicExtratoUrlAttribute(): string
    {
        return route('extrato.public.show', ['cliente' => $this]);
    }
}