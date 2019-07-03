<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialPollInvitedUsers extends Model
{

    const org_id        = 'org_id';
    const poll_group_id  = 'poll_group_id';
    const user_id    = 'user_id';

    const table       = 'social_poll_invited_users';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SocialPollInvitedUsers::table;

    protected $fillable = [SocialPollInvitedUsers::poll_group_id, SocialPollInvitedUsers::user_id];
}
