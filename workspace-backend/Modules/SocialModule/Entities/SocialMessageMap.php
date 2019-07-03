<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialMessageMap extends Model
{

    const user_id             = 'user_id';
    const social_message_id   = 'social_message_id';


    const table       = 'social_message_map';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SocialMessageMap::table;

    protected $fillable = [SocialMessageMap::user_id, SocialMessageMap::social_message_id];
}
