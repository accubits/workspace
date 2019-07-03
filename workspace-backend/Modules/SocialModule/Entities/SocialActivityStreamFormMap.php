<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialActivityStreamFormMap extends Model
{
    const activity_stream_master_id = 'activity_sm_id';
    const form_master_id = 'form_master_id';

    const table = 'social_activity_stream_form_map';

    protected $table = SocialActivityStreamFormMap::table;
    protected $fillable = [
        SocialActivityStreamFormMap::activity_stream_master_id,
        SocialActivityStreamFormMap::form_master_id
    ];
}
