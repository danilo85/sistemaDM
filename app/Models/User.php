<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; // 1. Adicione este import
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // 2. Adicione Notifiable aqui

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
        'logo_path',
        'signature_path',
        'cpf_cnpj',
        'whatsapp',
        'contact_email',
        'website_url',
        'behance_url',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
        ];
    }

    // =================================================================
    // Relações do Usuário
    // =================================================================

    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }

    public function orcamentos()
    {
        return $this->hasMany(Orcamento::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function autores()
    {
        return $this->hasMany(Autor::class);
    }

    public function categorias()
    {
        return $this->hasMany(Categoria::class);
    }

    public function bancos()
    {
        return $this->hasMany(Banco::class);
    }

    public function cartoesCredito()
    {
        return $this->hasMany(CartaoCredito::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    /**
     * Verifica se o usuário está online.
     */
    public function isOnline()
    {
        return $this->last_seen_at && $this->last_seen_at->diffInMinutes(now()) < 5;
    }
}
