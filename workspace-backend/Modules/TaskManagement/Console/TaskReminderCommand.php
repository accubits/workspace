<?php

namespace Modules\TaskManagement\Console;

use Illuminate\Console\Command;
use Modules\TaskManagement\Cron\CronTaskReminder;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TaskReminderCommand extends Command
{
    protected $cron;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:task-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron Job for Task Reminder email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CronTaskReminder $cron)
    {
        parent::__construct();
        $this->cron = $cron;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->cron->process();
    }
}
