<?php

namespace App\Console;

use App\Console\Commands\EventsClick;
use App\Console\Commands\FilesWalls;
use App\Console\Commands\LoginUsers;
use App\Console\Commands\RegisterUser;
use App\Console\Commands\ScenesVisit;
use App\Console\Commands\TypeWallsView;
use App\Console\Commands\WallsView;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        RegisterUser::class,
        LoginUsers::class,
        EventsClick::class,
        ScenesVisit::class,
        WallsView::class,
        TypeWallsView::class,
        FilesWalls::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('sync:registereduser')->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command('sync:loginuser')->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command('sync:eventsclick')->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command('sync:scenesvisit')->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command('sync:wallsview')->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command('sync:wallsview')->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command('sync:fileswalls')->everyThirtyMinutes()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
