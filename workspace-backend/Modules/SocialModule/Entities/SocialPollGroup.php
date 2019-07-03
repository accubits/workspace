<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialPollGroup extends Model
{
    const slug         = 'poll_group_slug';
    const org_id        = 'org_id';
    const is_poll_to_all  = 'is_poll_to_all';
    const poll_title         = 'poll_title';
    const poll_description   = 'poll_description';
    const creator_user_id    = 'creator_user_id';
    const status_id    = 'poll_group_status_id';
    


    const table       = 'social_poll_groups';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SocialPollGroup::table;

    protected $fillable = [SocialPollGroup::org_id, SocialPollGroup::is_poll_to_all];
}
