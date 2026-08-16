<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Throttle nomeado exclusivo para POST /login
        // 5 tentativas por minuto por IP — contador isolado das demais rotas
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        Blade::directive('cspNonce', fn() =>
            "<?php echo e(request()->attributes->get('csp_nonce', '')); ?>"
        );
    }
}
