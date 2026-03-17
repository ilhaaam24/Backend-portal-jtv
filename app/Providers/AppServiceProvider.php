<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;

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
        //
        config(['app.locale' => 'id']);
	    Carbon::setLocale('id');

        Schema::defaultStringLength(191);

        Blade::if('cekRolePermission', function (string $value) {
            return filled(auth()->user()->getPermissionsViaRoles()->where('name', $value));
        });
    }
}
