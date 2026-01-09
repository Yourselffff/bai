@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Titre de la page --}}
    <h1 class="text-3xl font-bold">Vie privée / Charte RGPD</h1>

    {{-- Messages de succès/erreur --}}
    @if(session('success'))
        <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- Section: Gestion du consentement --}}
    <div class="bg-white border rounded p-6">
        <h2 class="text-xl font-semibold mb-4">Gestion de votre consentement</h2>

        <div class="mb-4">
            <p class="text-sm text-gray-600 mb-2">
                Conformément au RGPD, vous pouvez gérer votre consentement à l'utilisation des cookies.
            </p>
            <p class="text-sm text-gray-600 mb-4">
                <strong>Votre choix actuel :</strong>
                @auth
                    @if(auth()->user()->cookie_consent === true)
                        <span class="text-green-600 font-semibold">Accepté</span>
                    @elseif(auth()->user()->cookie_consent === false)
                        <span class="text-red-600 font-semibold">Refusé</span>
                    @else
                        <span class="text-gray-600 font-semibold">Non défini</span>
                    @endif
                @else
                    <span class="text-gray-600">Non connecté</span>
                @endauth
            </p>
        </div>

        @auth
        <div class="flex gap-4">
            <form method="POST" action="{{ route('privacy.update-consent') }}">
                @csrf
                <input type="hidden" name="cookie_consent" value="1">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    ✓ Accepter les cookies
                </button>
            </form>

            <form method="POST" action="{{ route('privacy.update-consent') }}">
                @csrf
                <input type="hidden" name="cookie_consent" value="0">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    ✗ Refuser les cookies
                </button>
            </form>
        </div>
        @else
        <p class="text-sm text-gray-500 italic">Vous devez être connecté pour gérer votre consentement.</p>
        @endauth
    </div>

    {{-- Section: Charte RGPD --}}
    <div class="bg-white border rounded p-6">
        <h2 class="text-xl font-semibold mb-4">Charte RGPD</h2>

        <div class="space-y-4 text-sm text-gray-700">
            {{-- Vous pouvez rédiger votre charte ici --}}
            <p>Contenu de la charte à rédiger...</p>
        </div>
    </div>

</div>

@endsection
