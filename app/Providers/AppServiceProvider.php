<?php
namespace App\Providers;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;



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
        // Definimos un limitador para el login: máximo 3 intentos por combinación de email e IP
        RateLimiter::for('login-strict', function (Request $request) {
            return Limit::perMinute(3)->by($request->input('email').$request->ip())->response(function () {
                return back()->withErrors(['email' => 'Demasiados intentos fallidos. Cuenta deshabilitada temporalmente por 1 minuto.']);
            });
        });

        URL::forceScheme('https');
    }
}
