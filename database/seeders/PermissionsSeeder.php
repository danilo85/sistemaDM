<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa o cache de permissões para garantir que as novas sejam registadas
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- Criação das Permissões (as "chaves") ---
        Permission::firstOrCreate(['name' => 'acessar_orcamentos']);
        Permission::firstOrCreate(['name' => 'acessar_financeiro']);
        Permission::firstOrCreate(['name' => 'acessar_clientes']);
        Permission::firstOrCreate(['name' => 'acessar_autores']);
        Permission::firstOrCreate(['name' => 'gerenciar_usuarios']); // A permissão mais importante

        // --- Criação das Funções (os "conjuntos de chaves") ---

        // Função de Usuário Padrão
        $userRole = Role::firstOrCreate(['name' => 'User']);
        $userRole->givePermissionTo([
            'acessar_orcamentos',
            'acessar_financeiro',
            'acessar_clientes',
            'acessar_autores',
        ]);

        // Função de Admin (tem todas as permissões)
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        // --- Atribuição da Função de Admin ao seu usuário ---
        
        // Encontra o primeiro usuário (que é você)
        $user = User::orderBy('id', 'asc')->first();
        if ($user) {
            // Atribui a função de Admin a ele
            $user->assignRole('Admin');
        }
    }
}
