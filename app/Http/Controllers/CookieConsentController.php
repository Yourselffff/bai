<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

/**
 * Contrôleur pour gérer le consentement des cookies.
 * Principe Single Responsibility: gère uniquement les actions liées au consentement.
 */
class CookieConsentController extends Controller
{
    /**
     * Enregistre l'acceptation des cookies pour l'utilisateur connecté.
     */
    public function accept(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            $user->cookie_consent = true;
            $user->save();
        }

        return redirect()->back()->with('success', 'Consentement enregistré.');
    }

    /**
     * Enregistre le refus des cookies pour l'utilisateur connecté.
     */
    public function refuse(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            $user->cookie_consent = false;
            $user->save();
        }

        return redirect()->back()->with('success', 'Choix enregistré.');
    }
}
