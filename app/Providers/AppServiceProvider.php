<?php

namespace App\Providers;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Limitador de intentos para el login
        RateLimiter::for('login-strict', function (Request $request) {
            return Limit::perMinute(3)->by($request->input('email').$request->ip())->response(function () {
                return back()->withErrors(['email' => 'Demasiados intentos fallidos. Cuenta deshabilitada temporalmente por 1 minuto.']);
            });
        });

        // Reemplaza localhost por la IP únicamente en las URLs de correo de restablecimiento
        ResetPassword::createUrlUsing(function ($user, string $token) {
            $ipLocal = '192.168.0.14';
            $urlOriginal = route('password.reset', ['token' => $token, 'email' => $user->getEmailForPasswordReset()], true);
            
            return str_replace(['localhost', 'https://'], [$ipLocal, 'http://'], $urlOriginal);
        });

        // Reemplaza localhost por la IP en las URLs de verificación de correo
        VerifyEmail::createUrlUsing(function ($user) {
            $ipLocal = '192.168.0.14';
            
            $urlOriginal = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
            );

            return str_replace(['localhost', 'https://'], [$ipLocal, 'http://'], $urlOriginal);
        });

        // Forzar esquemas de URL a HTTPS para evitar alertas de seguridad en proxys como Ngrok
        if (request()->server->has('HTTP_X_FORWARDED_PROTO') || app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}