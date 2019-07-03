<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialAppreciationRecipientMap extends Model
{
    const user_id         = 'user_id';
    const appreciation_id = 'appreciation_id';
    const mark_as_read = 'mark_as_read';
    const read_datetime = 'read_datetime';

    const table = 'social_appreciation_recipient_maps';
    
    protected $table = SocialAppreciationRecipientMap::table;

    protected $fillable = [
        SocialAppreciationRecipientMap::mark_as_read,
        SocialAppreciationRecipientMap::user_id,
        SocialAppreciationRecipientMap::appreciation_id
    ];
}
