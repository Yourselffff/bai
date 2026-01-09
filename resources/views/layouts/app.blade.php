<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    {{-- SECURITY NOTE:
         No CSP, no security headers → intentional vulnerabilities !!!!!!!!!!!! n--}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sandbox</title>

    {{-- Tailwind (from Breeze build) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100" style="display: flex; flex-direction: column; min-height: 100vh;">

{{-- Top navigation bar --}}
<nav class="bg-white shadow mb-6">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between">

        <div class="flex space-x-4">
            <a href="{{ route('ideas.index') }}" class="font-bold">Ideas</a>
            <a href="{{ route('logs.index') }}">Logs</a>
            <a href="{{ route('redirect.vulnerable', ['url' => 'https://google.com']) }}">
                Open Redirect Test
            </a>
        </div>

        <div class="flex space-x-4">
            @auth
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-600">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>

    </div>
</nav>

{{-- Main content section --}}
<main style="flex: 1;">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="w-full bg-white border-t mt-8">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex justify-center items-center gap-8 text-sm text-gray-600">
            <span>&copy; 2026 BAI Sandbox</span>
            <span class="text-gray-400">|</span>
            <a href="{{ route('privacy.charter') }}" class="hover:text-blue-600 hover:underline">
                Charte RGPD
            </a>
            <span class="text-gray-400">|</span>
            <a href="{{ route('privacy.consent') }}" class="hover:text-blue-600 hover:underline">
                Gestion des cookies
            </a>
        </div>
    </div>
</footer>

{{-- Bandeau de consentement des cookies --}}
<x-cookie-consent-banner />

</body>
</html>
