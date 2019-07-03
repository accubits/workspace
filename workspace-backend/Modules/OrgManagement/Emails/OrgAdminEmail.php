<?php

namespace Modules\OrgManagement\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\UserManagement\Entities\User;

class OrgAdminEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $params)
    {
        $this->user   = $user;
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = ['resetLink' => env('RESET_PASSWORD_SUCCESS_LINK'),
            'password' => $this->params['orgAdminDefaultPassword'],
            'orgName' => $this->params['orgName']
        ];
        return $this->view('orgmanagement::emails.orgadmin-email-notification',$data);
    }
}
