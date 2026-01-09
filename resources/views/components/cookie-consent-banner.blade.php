{{--
    Composant Blade pour le bandeau de consentement des cookies.
    Affiche un modal bloquant tant que l'utilisateur n'a pas répondu.
--}}

@if(isset($showCookieBanner) && $showCookieBanner)
<div id="cookieConsentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">

    {{-- Contenu du modal --}}
    <div class="bg-white border rounded shadow-xl max-w-md w-full p-6">

        {{-- Titre --}}
        <h2 class="text-xl font-bold mb-4">
            Utilisation des cookies
        </h2>

        {{-- Description --}}
        <p class="text-sm text-gray-600 mb-4">
            Cette application utilise uniquement des cookies essentiels pour son fonctionnement.
        </p>

        {{-- Finalité --}}
        <div class="mb-4">
            <p class="text-sm font-semibold mb-2">Cookies utilisés :</p>
            <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                <li><strong>laravel_session</strong> : maintient votre connexion</li>
                <li><strong>XSRF-TOKEN</strong> : protège contre les attaques CSRF</li>
            </ul>
            <p class="text-xs text-gray-500 mt-2">
                Votre choix sera enregistré dans la base de données.
            </p>
        </div>

        {{-- Boutons --}}
        <div class="flex gap-2">
            <form method="POST" action="{{ route('cookie.accept') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Accepter
                </button>
            </form>

            <form method="POST" action="{{ route('cookie.refuse') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Refuser
                </button>
            </form>
        </div>

    </div>

</div>
@endif
