<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialPollOptions extends Model
{
    const org_id = 'org_id';
    const poll_id = 'poll_id';
    const poll_answeroption = 'poll_answeroption';
    const creator_user_id = 'creator_user_id';
    
    const table       = 'social_poll_options';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SocialPollOptions::table;

    protected $fillable = [SocialPollOptions::poll_id, SocialPollOptions::poll_answeroption];
}
