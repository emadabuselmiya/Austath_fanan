<?php

namespace App\Providers;

use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        try {
            $firebase = json_decode(file_get_contents(base_path('config/firebase.json')), true);

            if ($firebase) {
                Config::set('firebase', $firebase);
            }

        } catch (\Exception $e) {
        }
    }
}
