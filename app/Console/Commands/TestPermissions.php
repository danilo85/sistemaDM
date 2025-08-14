<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;

class TestPermissions extends Command
{
    protected $signature = 'test:permissions {user_id?}';
    protected $description = 'Testa as permissões de um usuário específico';

    public function handle()
    {
        $userId = $this->argument('user_id') ?? 1;
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("Usuário com ID {$userId} não encontrado.");
            return 1;
        }
        
        $this->info("Testando permissões para: {$user->name} (ID: {$user->id})");
        $this->line('');
        
        // Listar roles do usuário
        $this->info('Roles do usuário:');
        foreach ($user->roles as $role) {
            $this->line("- {$role->name}");
        }
        $this->line('');
        
        // Listar permissões diretas do usuário
        $this->info('Permissões diretas do usuário:');
        foreach ($user->permissions as $permission) {
            $this->line("- {$permission->name}");
        }
        $this->line('');
        
        // Testar permissões específicas
        $permissionsToTest = [
            'acessar_orcamentos',
            'gerenciar_usuarios',
            'acessar_clientes',
            'acessar_autores',
            'acessar_financeiro'
        ];
        
        $this->info('Teste de permissões:');
        foreach ($permissionsToTest as $permission) {
            $hasPermission = $user->hasPermissionTo($permission);
            $canGate = Gate::forUser($user)->allows($permission);
            
            $status = $hasPermission ? '✓' : '✗';
            $gateStatus = $canGate ? '✓' : '✗';
            
            $this->line("{$status} {$permission} (Spatie: {$status}, Gate: {$gateStatus})");
        }
        
        $this->line('');
        $this->info('Teste concluído!');
        
        return 0;
    }
}