<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\OrgManagement\Console\LicenseExpireStatus;
use Modules\SocialModule\Console\EventReminderCommand;
use Modules\TaskManagement\Console\ChangeHighPriorityTask;
use Modules\TaskManagement\Console\CreateRepeatTypeDayCommand;
use Modules\TaskManagement\Console\TaskReminderCommand;
use Modules\TaskManagement\Console\UpdateOverDueStatusCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CreateRepeatTypeDayCommand::class,
        UpdateOverDueStatusCommand::class,
        ChangeHighPriorityTask::class,
        TaskReminderCommand::class,
        LicenseExpireStatus::class,
        EventReminderCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cron:repeat-task-day')->everyThirtyMinutes();
        $schedule->command('cron:task-overdue')->everyMinute();
        $schedule->command('cron:high-priority')->everyMinute();
        $schedule->command('cron:task-reminder')->everyMinute();
        $schedule->command('cron:license-expire')->everyThirtyMinutes();
        //$schedule->command('cron:event-reminder')->everyMinute();
        // $schedule->command('inspire')
        //          ->hourly();
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
