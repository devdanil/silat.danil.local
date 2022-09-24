<?php

namespace App\Console;

use App\Console\Commands\SendMailPendaftaranCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected $commands = [
        SendMailPendaftaranCommand::class
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('emails:pendaftaran')->when(\App\Models\Pendaftaran::where('sendmail_at', null)->count() > 0)->everyMinute()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
