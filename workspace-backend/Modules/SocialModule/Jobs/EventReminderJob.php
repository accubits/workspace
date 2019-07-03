<?php

namespace Modules\SocialModule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Modules\SocialModule\Emails\EventReminderEmail;

class EventReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $eventReminder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($eventReminder)
    {
        $this->eventReminder = $eventReminder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->eventReminder->members as $key => $members) {
            $event = [
                "eventStartDate" => $this->eventReminder->event_start_date,
                "memberName" => $this->eventReminder->memberNames[$key],
                "eventTitle" => $this->eventReminder->eventTitle
            ];

            Mail::to($members)->send(new EventReminderEmail($event));
        }
    }
}
