<?php

namespace Modules\TaskManagement\Emails;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emailParams)
    {
        $this->params = $emailParams;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $timezone = 'UTC';
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
        ];

        return $this->view('taskmanagement::emails.task-reminder', $data);
    }
}
