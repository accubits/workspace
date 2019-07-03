<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialActivityStreamType extends Model
{
    const name                  = 'name';
    const table                  = 'social_activity_stream_types';

    protected $table = SocialActivityStreamType::table;
    protected $fillable = [SocialActivityStreamType::name];
}
