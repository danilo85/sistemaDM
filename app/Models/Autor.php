<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    // ADICIONE ESTA LINHA:
    protected $table = 'autores';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'contact',
        'bio',
        'is_complete',
        'logo',
        'cor',
    ];

    // Constantes para as cores disponíveis
    const CORES_DISPONIVEIS = [
        'blue' => 'Azul',
        'green' => 'Verde',
        'pink' => 'Rosa',
        'yellow' => 'Amarelo',
        'orange' => 'Laranja',
        'white' => 'Branco',
    ];
    
    protected $casts = [
            'is_complete' => 'boolean',
        ];
        
    // Adicione dentro da classe:
    public function orcamentos(): BelongsToMany
    {
        return $this->belongsToMany(Orcamento::class, 'autor_orcamento');
    }
}