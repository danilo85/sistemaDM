<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <-- ADICIONE ESTA LINHA

class CartaoCredito extends Model
{
    use HasFactory;

    protected $table = 'cartoes_credito';

    protected $fillable = [
        'user_id',
        'nome',
        'limite_credito',
        'dia_fechamento',
        'dia_vencimento',
        'bandeira',
    ];

    // ADICIONE ESTE MÉTODO
    public function getBandeiraPathAttribute(): ?string
        {
            if ($this->bandeira) {
                // Transforma "American Express" em "american-express.png"
                $fileName = Str::slug($this->bandeira) . '.png';
                $path = public_path('images/logos/' . $fileName);

                if (file_exists($path)) {
                    return asset('images/logos/' . $fileName);
                }
            }
            return null;
        }

        // Adicione este método dentro da classe CartaoCredito
        public function transacoes(): MorphMany
        {
            return $this->morphMany(Transacao::class, 'transacionavel');
        }
}