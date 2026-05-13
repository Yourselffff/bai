<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = Str::random(32);
        Vite::useCspNonce($nonce);

        $response = $next($request);

        $isLocal = config('app.env') === 'local';
        $viteHttp = $isLocal ? 'http://192.168.61.173:5173' : '';
        $viteWs   = $isLocal ? 'ws://192.168.61.173:5173'   : '';

        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'nonce-{$nonce}' {$viteHttp}; " .
            "style-src 'self' 'nonce-{$nonce}' {$viteHttp}; " .
            "img-src 'self' data: blob:; " .
            "font-src 'self'; " .
            "connect-src 'self' {$viteHttp} {$viteWs}; " .
            "form-action 'self'; " .
            "base-uri 'self'; " .
            "object-src 'none'; " .
            "frame-ancestors 'none';"
        );

        return $response;
    }
}
