<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    
    public function boot()
    {
        Paginator::useBootstrap();
        //

        DB::listen(function ($q) {
            Log::info('SQL:', ['sql' => $q->sql, 'bindings' => $q->bindings, 'time_ms' => $q->time]);
        });
    }
}
