<?php

namespace App\Http\Middleware;

use App\Models\User; // Importe o seu modelo User
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserLastSeenAt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se há um usuário autenticado
        if (Auth::check()) {
            $user = Auth::user();
            // Garante que temos uma instância do nosso modelo User antes de chamar o método update
            if ($user instanceof User) {
                $user->update(['last_seen_at' => now()]);
            }
        }

        // Continua com a requisição normalmente
        return $next($request);
    }
}
