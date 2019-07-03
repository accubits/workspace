<?php

namespace Modules\TaskManagement\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Modules\TaskManagement\Emails\TaskReminderEmail;
use Modules\UserManagement\Entities\User;
use Monolog\Logger;

class TaskReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $params;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emailParams)
    {
        $this->params = $emailParams;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
/*        $user = User::where(User::email, $this->params->email)->first();
//dd($this->params);*/
        Mail::to($this->params->email)->send(new TaskReminderEmail($this->params));
/*        dd("as");
        foreach ($this->emailArrays as $email) {
            Mail::to($email)->send(new TaskReminderEmail($email));
        }*/
    }
}
