<?php

namespace Modules\OrgManagement\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\UserManagement\Entities\User;

class UserVerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $verifyUrl = env('BASE_URL').'verify/'. $this->user->remember_token;
        return $this->view('orgmanagement::emails.verifyUser',['user' => $this->user, 'verfiy' => $verifyUrl]);
    }
}
