<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialPoll extends Model
{
    const slug         = 'poll_slug';
    const poll_group_id = 'poll_group_id';
    const allow_multiple_choice = 'allow_multiple_choice';
    const question = 'question';
    const org_id = 'org_id';
    const creator_user_id = 'creator_user_id';
    
    const table       = 'social_poll';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SocialPoll::table;

    protected $fillable = [SocialPoll::slug, SocialPoll::question];
}
