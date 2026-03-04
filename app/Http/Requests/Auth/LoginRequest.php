<?php

namespace App\Http\Requests\Auth;

use App\Services\ActionLogService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey(), 600);

            // LOG fichier (historique existant)
            Log::warning('Tentative de connexion échouée', [
                'email' => $this->input('email'),
                'ip' => $this->ip(),
                'user_agent' => $this->userAgent(),
            ]);

            // LOG base de données — côté serveur, l'utilisateur ne peut pas modifier ce log
            // user_id est null car la connexion a échoué (pas d'utilisateur authentifié)
            ActionLogService::log(
                action: 'login_failed',
                userId: null,
                dataAfter: ['email' => $this->input('email')]
            );

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 2)) {
            return;
        }

        event(new Lockout($this));

        // Log du blocage de compte
        Log::warning('Compte bloqué - trop de tentatives', [
            'email' => $this->input('email'),
            'ip' => $this->ip(),
            'user_agent' => $this->userAgent(),
        ]);

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
