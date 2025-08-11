<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Banco extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nome',
        'tipo_conta',
        'saldo_inicial',
        'logo',
    ];

    public function getLogoPathAttribute(): ?string
    {
        if ($this->nome) {
            $fileName = Str::slug($this->nome) . '.png';
            $path = public_path('images/logos/' . $fileName);

            if (file_exists($path)) {
                return asset('images/logos/' . $fileName);
            }
        }
        return null;
    }
    // Adicione este método dentro da classe Banco
    public function transacoes(): MorphMany
    {
        return $this->morphMany(Transacao::class, 'transacionavel');
    }
    // Adicione este método dentro da classe Banco
    public function getSaldoAtualAttribute(): float
    {
        // 1. Pega todas as receitas associadas a este banco
        $receitas = $this->transacoes()->where('tipo', 'receita')->sum('valor');

        // 2. Pega todas as despesas associadas a este banco
        $despesas = $this->transacoes()->where('tipo', 'despesa')->sum('valor');

        // 3. Calcula: Saldo Inicial + Receitas - Despesas
        return $this->saldo_inicial + $receitas - $despesas;
    }
}