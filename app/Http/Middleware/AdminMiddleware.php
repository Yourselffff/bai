<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Vérifie que l'utilisateur est administrateur.
     * Bloque l'accès si l'utilisateur n'est pas admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
