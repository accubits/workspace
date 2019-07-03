<?php

namespace Modules\TaskManagement\Console;

use Illuminate\Console\Command;
use Modules\TaskManagement\Cron\CronRepeatTypeDay;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateRepeatTypeDayCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:repeat-task-day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron job for repeat task by day';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public $cron;
    public function __construct(CronRepeatTypeDay $cron)
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
        //$repeatTask =
    }


}
