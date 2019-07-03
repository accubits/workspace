<?php

namespace Modules\TaskManagement\Console;

use Illuminate\Console\Command;
use Modules\TaskManagement\Cron\CronOverDueStatus;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateOverDueStatusCommand extends Command
{

    public $cron;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:task-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron Job to update task overdue status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CronOverDueStatus $cron)
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
