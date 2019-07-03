<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialActivityStreamUser extends Model
{
    const activity_stream_master_id = 'activity_sm_id';
    const to_user_id = 'to_user_id';

    const table = 'social_activitystm_users';

    protected $table = SocialActivityStreamUser::table;
    protected $fillable = [
        SocialActivityStreamUser::activity_stream_master_id,
        SocialActivityStreamUser::to_user_id
    ];
}
