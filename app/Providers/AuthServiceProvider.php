<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Order;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Определение Gate проверки на редактирование заявок
        Gate::define('edit-orders', function ($user) {
            return ($user->name === config('app.admin_name')) && ($user->email === config('app.admin_email'));
        });
    }
}
