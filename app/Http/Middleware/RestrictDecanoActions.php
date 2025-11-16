<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictDecanoActions
{
    /**
     * Handle an incoming request.
     *
     * Restringe acciones de modificación/eliminación para decanos.
     * Los decanos solo pueden VER información, no modificarla.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si el usuario tiene rol admin o super-admin, permitir
        if ($user && $user->hasAnyRole(['admin', 'super-admin'])) {
            return $next($request);
        }

        // Si el usuario es decano, verificar el método
        if ($user && $user->hasRole('decano')) {
            $method = $request->method();
            $routeName = $request->route()->getName();

            // Permitir solo métodos GET (index, show, create form view)
            // Bloquear POST, PUT, PATCH, DELETE (store, update, destroy)
            if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                // Rutas permitidas para decano incluso con POST/PUT/DELETE
                $allowedRoutes = [
                    'admin.habilitaciones.store',
                    'admin.habilitaciones.cancelar',
                    'admin.asistencia.store',
                    'admin.carga-academica.store',
                    'admin.carga-academica.update',
                    'admin.carga-academica.destroy',
                    'admin.carga-academica.verificar-conflictos',
                ];

                if (!in_array($routeName, $allowedRoutes)) {
                    abort(403, 'No tienes permisos para realizar esta acción. Solo el administrador puede modificar o eliminar estos recursos.');
                }
            }
        }

        return $next($request);
    }
}
