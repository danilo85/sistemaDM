<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity; 
use Spatie\Activitylog\LogOptions;          
use Illuminate\Database\Eloquent\Relations\HasOne; // 1. Adicione este import

class Orcamento extends Model
{
    use HasFactory, LogsActivity;

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'user_id',
        'numero',
        'titulo',
        'descricao',
        'cliente_id',
        'user_id',
        'status',
        'valor_total',
        'condicoes_pagamento',
        'data_emissao',
        'data_validade',
        'prazo_entrega_dias',
        'data_inicio_projeto',
        'visivel',
        'fixado',
    ];
        /**
         * Os atributos que devem ser convertidos para tipos nativos.
         *
         * @var array
         */
        protected $casts = [
            'data_emissao' => 'datetime',
            'data_validade' => 'datetime',
            'data_inicio_projeto' => 'datetime',
            'updated_at' => 'datetime',

        ];
        // 4. Define O QUE deve ser registrado
            public function getActivitylogOptions(): LogOptions
            {
                return LogOptions::defaults()
                    ->logOnly(['status', 'valor_total', 'titulo', 'prazo_entrega_dias']) // Queremos registrar mudanças apenas nesses campos
                    ->setDescriptionForEvent(fn(string $eventName) => "Orçamento {$eventName}") // Ex: "Orçamento updated"
                    ->useLogName('Orcamento') // Nome do log
                    ->logOnlyDirty(); // Só registra se algo realmente mudou
            }
                /**
         * O método "boot" do model.
         */
        protected static function boot()
        {
            parent::boot();

            // Evento que é disparado ANTES de um novo orçamento ser criado
            static::creating(function ($orcamento) {
                // Gera um UUID (token) único e o atribui ao novo orçamento
                $orcamento->token = Str::uuid();
            });
        }
        /**
         * Define o relacionamento: um Orçamento PERTENCE A UM Cliente.
         */
        public function cliente(): BelongsTo
        {
            return $this->belongsTo(Cliente::class);
        }

        /**
         * Define o relacionamento: um Orçamento PERTENCE A UM User (autor).
         */
        public function autor(): BelongsTo
        {
            return $this->belongsTo(User::class, 'user_id');
        }

        /**
         * Define o relacionamento: um Orçamento TEM MUITOS OrcamentoItem.
         */
        public function itens(): HasMany
        {
            return $this->hasMany(OrcamentoItem::class);
        }
        // Adicione dentro da classe:
        public function autores(): BelongsToMany
        {
            return $this->belongsToMany(Autor::class, 'autor_orcamento');
        }
        /**
         * Retorna a URL pública e segura para este orçamento.
         */
        public function getPublicUrlAttribute(): string
        {
            return route('orcamentos.public.show', ['orcamento' => $this]);

        }

        public function pagamentos(): \Illuminate\Database\Eloquent\Relations\HasMany
        {
            return $this->hasMany(Pagamento::class);
        }
        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class, 'user_id');
        }
    // 2. Adicione esta nova relação
        public function portfolio(): HasOne
        {
            return $this->hasOne(Portfolio::class);
        }
    
}