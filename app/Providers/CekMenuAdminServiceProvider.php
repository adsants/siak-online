<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CekMenuAdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //    
        require_once app_path() . '/Helpers/cekmenuadmin.php';

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
