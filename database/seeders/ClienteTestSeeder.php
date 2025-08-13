<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClienteTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Busca o primeiro usuário ou cria um se não existir
        $user = User::first() ?? User::factory()->create();

        // Cria 6 clientes completos
        Cliente::factory(6)->create([
            'user_id' => $user->id,
        ]);

        // Cria 3 clientes incompletos (sem email ou telefone)
        Cliente::factory(3)->incomplete()->create([
            'user_id' => $user->id,
        ]);

        // Cria 1 cliente com apenas nome (mais incompleto)
        Cliente::factory(1)->nameOnly()->create([
            'user_id' => $user->id,
        ]);

        $this->command->info('10 clientes de teste criados com sucesso!');
    }
}