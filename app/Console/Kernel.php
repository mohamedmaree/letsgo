<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel{
    
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\LaterOrders::class,
        Commands\updateCaptainsPlans::class,
        Commands\checkWaslEligibility::class,
        Commands\registerTripsToWasl::class,
        Commands\sendCaptainsLocationsToWasl::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule){

        $schedule->command('laterorders:hourly')
                  // ->everyFiveMinutes();
                  // ->hourly();
                    ->everyThirtyMinutes();
        $schedule->command('updateCaptainsPlans:monthly')
                  // ->everyFiveMinutes();
                  // ->hourly();
                    ->monthly();
        $schedule->command('checkWaslEligibility:daily')
                  // ->everyFiveMinutes();
                  // ->hourly();
                    ->daily();
        $schedule->command('registerTripsToWasl:hourly')
                    ->everyMinute();
                  // ->everyFiveMinutes();
                  // ->hourly();
        $schedule->command('sendCaptainsLocationsToWasl:15minutes')
                    ->everyMinute();
        // $schedule->call(function () {
        //     User::where('spam_count', '>', 100)
        //         ->get()
        //         ->each
        //         ->delete();
        // })->hourly();                 
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
