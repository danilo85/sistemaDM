<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importante adicionar esta linha
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. O usuário está logado? E tem a role 'Admin'?
        if (Auth::check() && Auth::user()->hasRole('Admin')) {
            // 2. Se sim, pode seguir para a próxima etapa (acessar a página).
            return $next($request);
        }

        // 3. Se não for admin, barre o acesso com uma página de erro "403 Acesso Proibido".
        abort(403, 'ACESSO NEGADO');
    }
}