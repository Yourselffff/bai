<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware pour vérifier si l'utilisateur a donné son consentement aux cookies.
 * Si le consentement n'est pas défini (null), l'utilisateur verra le bandeau.
 * Principe Open/Closed: ajoute une nouvelle fonctionnalité sans modifier le code existant.
 */
class CheckCookieConsent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // On laisse passer la requête, mais on transmet l'info au layout
        // pour afficher le bandeau si nécessaire
        $user = $request->user();

        if ($user && $user->cookie_consent === null) {
            // L'utilisateur n'a pas encore répondu
            view()->share('showCookieBanner', true);
        } else {
            view()->share('showCookieBanner', false);
        }

        return $next($request);
    }
}
