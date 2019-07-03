<?php

namespace Modules\SocialModule\Console;

use Illuminate\Console\Command;
use Modules\SocialModule\Cron\CronEventReminder;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EventReminderCommand extends Command
{
    protected $cron;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:event-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cron for event reminder email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CronEventReminder $cron)
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
