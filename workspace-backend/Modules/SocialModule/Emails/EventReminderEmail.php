<?php

namespace Modules\SocialModule\Emails;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $events;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($events)
    {
        $this->events = $events;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $data = [
            'name' => $this->events['memberName'],
            'event' => $this->events['eventTitle'],
            //'email' => $this->params->email,
            'dueDate' => $this->events['eventStartDate']
        ];

        /*$timezone = 'UTC';
        if ($this->params->orgTimezone) {
            $timezone = $this->params->orgTimezone;
        } else if ($this->params->userTimezone) {
            $timezone = $this->params->userTimezone;
        }
        $dueDate = Carbon::parse($this->params->end_date)->timezone($timezone)->format('M d, Y, h:i:s A');

        $baseUrlLink = env('FRONT_END_BASEURL');
        $taskUrl = $baseUrlLink. 'authorized/task/task-d/(task-overview//detailpopup:task-detail/' .$this->params->slug. ')';
        $data = [
            'name' => $this->params->name,
            'task' => $this->params->title,
            'taskUrl' => $taskUrl,
            'email' => $this->params->email,
            'dueDate' => $dueDate
        ];*/

        return $this->view('socialmodule::emails.event-reminder', $data);
    }
}
