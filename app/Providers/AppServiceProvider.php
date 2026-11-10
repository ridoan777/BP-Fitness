<?php

namespace App\Providers;

use App\Helpers\Errors\ExceptionHandling;
use App\Models\System\Security\CustomPersonalAccessToken;
use App\Models\Users\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Cashier::useCustomerModel(User::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(CustomPersonalAccessToken::class);

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(require app_path('Providers/DatabaseServiceLoader.php'));
        }

        Gate::before(function ($user, string $ability) {
            // if (str_starts_with($ability, 'subscribe-')) {
            //     return null;
            // }
            // return $user->hasRole('super_admin') ? true : null;
            return $user->hasRoleKey('SUPER_ADMIN') ? true : null;
        });
    }
}
