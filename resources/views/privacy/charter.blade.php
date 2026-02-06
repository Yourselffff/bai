@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Titre de la page --}}
    <h1 class="text-3xl font-bold">Charte RGPD</h1>

    {{-- 1. Responsable de traitement --}}
    <div class="bg-white border rounded p-6">
        <h2 class="text-lg font-semibold mb-2">Responsable de traitement</h2>
        <p class="text-sm text-gray-700">
            BAI Sandbox - Application pédagogique<br>
            Contact : contact@bai-sandbox.fr
        </p>
    </div>

    {{-- 2. Données collectées --}}
    <div class="bg-white border rounded p-6">
        <h2 class="text-lg font-semibold mb-2">Données collectées</h2>
        <ul class="text-sm text-gray-700 list-disc list-inside space-y-1">
            <li>Nom et adresse e-mail</li>
            <li>Mot de passe (stocké chiffré)</li>
            <li>Adresse IP et navigateur utilisé</li>
            <li>Consentement aux cookies</li>
            <li>Idées et commentaires publiés</li>
        </ul>
    </div>

    {{-- 3. Finalités du traitement --}}
    <div class="bg-white border rounded p-6">
        <h2 class="text-lg font-semibold mb-2">Finalités du traitement</h2>
        <ul class="text-sm text-gray-700 list-disc list-inside space-y-1">
            <li>Gestion de votre compte utilisateur</li>
            <li>Publication et affichage de vos idées et commentaires</li>
            <li>Sécurité de l'application (protection CSRF, sessions)</li>
        </ul>
    </div>

    {{-- 4. Durée de conservation --}}
    <div class="bg-white border rounded p-6">
        <h2 class="text-lg font-semibold mb-2">Durée de conservation</h2>
        <p class="text-sm text-gray-700">
            Vos données sont conservées tant que votre compte est actif.<br>
            Suppression des données sous 30 jours après suppression du compte.
        </p>
    </div>

    {{-- 5. Droits des utilisateurs --}}
    <div class="bg-white border rounded p-6">
        <h2 class="text-lg font-semibold mb-2">Vos droits</h2>
        <ul class="text-sm text-gray-700 list-disc list-inside space-y-1">
            <li>Accès à vos données</li>
            <li>Rectification de vos données</li>
            <li>Suppression de vos données</li>
            <li>Opposition au traitement</li>
            <li>Portabilité de vos données</li>
        </ul>
    </div>

    {{-- 6. Contact --}}
    <div class="bg-white border rounded p-6">
        <h2 class="text-lg font-semibold mb-2">Contact</h2>
        <p class="text-sm text-gray-700">
            Pour exercer vos droits : <a href="mailto:contact@bai-sandbox.fr" class="text-blue-600 hover:underline">contact@bai-sandbox.fr</a><br>
            <span class="text-xs text-gray-500">Vous pouvez également contacter la CNIL : <a href="https://www.cnil.fr" class="text-blue-600 hover:underline" target="_blank">www.cnil.fr</a></span>
        </p>
    </div>

</div>

@endsection
