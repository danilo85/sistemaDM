<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banco;
use App\Models\User;

class BancosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Busca o primeiro usuário admin para associar os bancos
        $user = User::whereHas('roles', function($query) {
            $query->where('name', 'Admin');
        })->first();

        // Se não encontrar admin, usa o primeiro usuário disponível
        if (!$user) {
            $user = User::first();
        }

        if (!$user) {
            $this->command->error('Nenhum usuário encontrado. Execute primeiro o UserSeeder.');
            return;
        }

        $bancos = [
            [
                'user_id' => $user->id,
                'nome' => 'Santander',
                'tipo_conta' => 'Corrente',
                'saldo_inicial' => 15750.80,
            ],
            [
                'user_id' => $user->id,
                'nome' => 'Bradesco',
                'tipo_conta' => 'Poupança',
                'saldo_inicial' => 8920.45,
            ],
            [
                'user_id' => $user->id,
                'nome' => 'Caixa',
                'tipo_conta' => 'Corrente',
                'saldo_inicial' => 3240.15,
            ],
            [
                'user_id' => $user->id,
                'nome' => 'Itaú',
                'tipo_conta' => 'Investimento',
                'saldo_inicial' => 25680.90,
            ],
            [
                'user_id' => $user->id,
                'nome' => 'Inter',
                'tipo_conta' => 'Corrente',
                'saldo_inicial' => 12450.30,
            ],
        ];

        foreach ($bancos as $bancoData) {
            // Verifica se o banco já existe para este usuário
            $bancoExistente = Banco::where('user_id', $bancoData['user_id'])
                                  ->where('nome', $bancoData['nome'])
                                  ->where('tipo_conta', $bancoData['tipo_conta'])
                                  ->first();

            if (!$bancoExistente) {
                Banco::create($bancoData);
                $this->command->info("Banco {$bancoData['nome']} ({$bancoData['tipo_conta']}) criado com sucesso!");
            } else {
                $this->command->warn("Banco {$bancoData['nome']} ({$bancoData['tipo_conta']}) já existe.");
            }
        }

        $this->command->info('Seeder de bancos executado com sucesso!');
    }
}