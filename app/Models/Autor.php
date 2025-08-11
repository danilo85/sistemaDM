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
        'contact', // Adicionei os campos da sua migration aqui
        'bio',     // para garantir que estão corretos
        'is_complete',
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