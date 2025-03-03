<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Register services.
     */
    public function register(): void
    {
        \Log::info('RouteServiceProvider register method called');
    }

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        \Log::info('RouteServiceProvider boot method called');

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Register web routes
        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        // Manually register API routes
        Route::middleware('api')
            ->prefix('api')
            ->group(function () {
                Route::get('/test', function () {
                    return response()->json(['message' => 'API is working!']);
                });

                Route::get('/delivery', [\App\Http\Controllers\DeliveryPointController::class, 'index']);
                Route::post('/delivery', [\App\Http\Controllers\DeliveryPointController::class, 'store']);
                Route::get('/delivery/{id}', [\App\Http\Controllers\DeliveryPointController::class, 'show']);
            });

        // Log all registered routes
        \Log::info('All registered routes:');
        foreach (Route::getRoutes() as $route) {
            \Log::info($route->methods()[0] . ' ' . $route->uri() . ' => ' . $route->getActionName());
        }
    }
}
