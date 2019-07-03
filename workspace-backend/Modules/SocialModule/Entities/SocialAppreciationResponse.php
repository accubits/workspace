<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialAppreciationResponse extends Model
{
    const slug = 'slug';
    const org_id = 'org_id';
    const appreciation_id = 'appreciation_id';
    const user_id = 'user_id';
    const response_type_id = 'response_type_id';
    
    const table       = 'social_appreciation_responses';

    protected $table = SocialAppreciationResponse::table;
    protected $fillable = [
        SocialAppreciationResponse::slug,
        SocialAppreciationResponse::org_id,
        SocialAppreciationResponse::appreciation_id,
        SocialAppreciationResponse::user_id,
        SocialAppreciationResponse::response_type_id
        ];
}
