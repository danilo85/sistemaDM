<?php

namespace Database\Seeders;

use App\Models\CartaoCredito;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Busca o primeiro usuário para associar os cartões
        $user = User::first();
        
        if (!$user) {
            $this->command->error('Nenhum usuário encontrado. Execute primeiro o UserSeeder.');
            return;
        }

        $cartoes = [
            [
                'user_id' => $user->id,
                'nome' => 'Visa Platinum',
                'bandeira' => 'Visa',
                'limite_credito' => 5000.00,
                'dia_fechamento' => 15,
                'dia_vencimento' => 10,
            ],
            [
                'user_id' => $user->id,
                'nome' => 'Mastercard Gold',
                'bandeira' => 'Mastercard',
                'limite_credito' => 8000.00,
                'dia_fechamento' => 20,
                'dia_vencimento' => 15,
            ],
            [
                'user_id' => $user->id,
                'nome' => 'Elo Mais',
                'bandeira' => 'Elo',
                'limite_credito' => 3000.00,
                'dia_fechamento' => 5,
                'dia_vencimento' => 25,
            ],
            [
                'user_id' => $user->id,
                'nome' => 'American Express Green',
                'bandeira' => 'American Express',
                'limite_credito' => 12000.00,
                'dia_fechamento' => 25,
                'dia_vencimento' => 20,
            ],
            [
                'user_id' => $user->id,
                'nome' => 'Hipercard Black',
                'bandeira' => 'Hipercard',
                'limite_credito' => 15000.00,
                'dia_fechamento' => 10,
                'dia_vencimento' => 5,
            ],
        ];

        foreach ($cartoes as $cartaoData) {
            CartaoCredito::create($cartaoData);
        }

        $this->command->info('5 cartões de crédito criados com sucesso!');
    }
}