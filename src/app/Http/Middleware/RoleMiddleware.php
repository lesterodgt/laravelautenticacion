<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Forma 1 (recomendada): desde el Request (usa el guard por defecto 'web')
        $user = $request->user(); // equivalente a auth()->user()

        if (!$user) {
            return redirect()->guest(route('login'));
        }
        // Verifica el rol simple (columna 'role' en users)
        if (! in_array($user->role, $roles, true)) {
            abort(403, 'No tienes permisos para acceder a esta ruta....12315646');
        }
        return $next($request);
    }
}
