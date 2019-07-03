<?php

namespace Modules\TaskManagement\Console;

use Illuminate\Console\Command;
use Modules\TaskManagement\Cron\CronHighPriority;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ChangeHighPriorityTask extends Command
{
    public $cron;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:high-priority';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change tasks to high priority when due date before 24hrs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CronHighPriority $cron)
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
