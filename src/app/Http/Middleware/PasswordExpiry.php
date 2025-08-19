<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user) {

            $mesesCaducidad = 2;
            $ultima = $user->password_changed_at ?? $user->created_at;
            $ruta = $request->route()?->getName();
            $rutasPermitidas = [
                'profile.edit', 'profile.update',
                'password.request', 'password.email', 'password.reset', 'password.store',
                'logout',
            ];
            $estaEnRutasPermitidas = $ruta && in_array($ruta, $rutasPermitidas, true);

            if (!$estaEnRutasPermitidas && $ultima && $ultima->addMonths($mesesCaducidad)->isPast()) {
                return redirect()
                    ->route('profile.edit')
                    ->with('warning', 'Tu contraseña ha expirado. Por favor, actualízala para continuar.');
            }
        }
        return $next($request);
    }
}
