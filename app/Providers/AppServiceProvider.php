<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){
        Schema::defaultStringLength(191);
        Validator::extend('alpha_spaces', function ($attribute, $value) {
          return preg_match('/^[\pL\s]+$/u', $value);
          });
        \Carbon\Carbon::setLocale(config('app.locale'));

        $current_country = currentCountry();
        if($current_country['time_zone']){
            date_default_timezone_set($current_country['time_zone']);            
        }else{
            date_default_timezone_set('Asia/Riyadh');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
