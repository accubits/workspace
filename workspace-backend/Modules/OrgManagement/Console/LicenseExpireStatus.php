<?php

namespace Modules\OrgManagement\Console;

use Illuminate\Console\Command;
use Modules\OrgManagement\Cron\CronLicenseExpireStatus;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LicenseExpireStatus extends Command
{
    public $cron;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:license-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to expire the license ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CronLicenseExpireStatus $cron)
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
