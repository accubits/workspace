<?php

namespace Modules\OrgManagement\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Modules\OrgManagement\Emails\OrgAdminEmail;

class OrgEmailNotificationQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $emailParams)
    {
        $this->user   = $user;
        $this->params = $emailParams;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new OrgAdminEmail($this->user, $this->params));
    }
}
