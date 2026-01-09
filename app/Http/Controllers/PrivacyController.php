<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Contrôleur pour la gestion de la vie privée et du RGPD.
 * Principe Single Responsibility: gère uniquement la page RGPD et la modification du consentement.
 */
class PrivacyController extends Controller
{
    /**
     * Affiche la page de la charte RGPD.
     */
    public function charter(): View
    {
        return view('privacy.charter');
    }

    /**
     * Affiche la page de gestion du consentement.
     */
    public function consent(): View
    {
        return view('privacy.consent');
    }

    /**
     * Met à jour le consentement de l'utilisateur.
     */
    public function updateConsent(Request $request): RedirectResponse
    {
        $request->validate([
            'cookie_consent' => 'required|boolean',
        ]);

        $user = $request->user();

        if ($user) {
            $user->cookie_consent = $request->input('cookie_consent');
            $user->save();

            $message = $request->input('cookie_consent')
                ? 'Vous avez accepté les cookies.'
                : 'Vous avez refusé les cookies.';

            return redirect()->route('privacy.consent')->with('success', $message);
        }

        return redirect()->route('privacy.consent')->with('error', 'Erreur lors de la mise à jour.');
    }
}
