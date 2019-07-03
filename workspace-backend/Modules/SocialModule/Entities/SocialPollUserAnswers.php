<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialPollUserAnswers extends Model
{
    const org_id = 'org_id';
    const poll_id = 'poll_id';
    const poll_option_id = 'poll_option_id';
    const user_id = 'user_id';
    
    const table       = 'social_poll_user_answers';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SocialPollUserAnswers::table;

    protected $fillable = [SocialPollUserAnswers::poll_id, SocialPollUserAnswers::poll_option_id];
}
