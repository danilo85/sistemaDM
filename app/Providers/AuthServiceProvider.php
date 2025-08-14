<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Registrar gates para as permissões do Spatie
        Gate::before(function ($user, $ability) {
            // Se o usuário é admin, tem acesso a tudo
            if ($user->hasRole('Admin')) {
                return true;
            }
            
            // Verificar se o usuário tem a permissão específica
            return $user->hasPermissionTo($ability);
        });
        
        // Gates específicos para cada setor
        Gate::define('acessar_orcamentos', function ($user) {
            return $user->hasRole('Admin') || $user->hasPermissionTo('acessar_orcamentos');
        });
        
        Gate::define('gerenciar_usuarios', function ($user) {
            return $user->hasRole('Admin') || $user->hasPermissionTo('gerenciar_usuarios');
        });
        
        Gate::define('acessar_clientes', function ($user) {
            return $user->hasRole('Admin') || $user->hasPermissionTo('acessar_clientes');
        });
        
        Gate::define('acessar_autores', function ($user) {
            return $user->hasRole('Admin') || $user->hasPermissionTo('acessar_autores');
        });
        
        Gate::define('acessar_financeiro', function ($user) {
            return $user->hasRole('Admin') || $user->hasPermissionTo('acessar_financeiro');
        });
    }
}