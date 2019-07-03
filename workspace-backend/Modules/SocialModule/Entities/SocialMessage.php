<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialMessage extends Model
{

    const slug         = 'message_slug';
    const is_message_to_all  = 'is_message_to_all';
    const title         = 'message_title';
    const description   = 'message_description';
    const org_id        = 'org_id';
    const creator_user_id    = 'creator_user_id';
    
    const table       = 'social_message';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SocialMessage::table;

    protected $fillable = [SocialMessage::title, SocialMessage::description];
}
