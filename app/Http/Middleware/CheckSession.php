<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        // Si no hay sesión activa, redirige al login
        if (!session()->has('user')) {
            return redirect()->route('login');
        }

        // Impedir que el navegador use caché (así no puede volver con "Atrás")
        $response = $next($request);

        return $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}
