<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialResponseType extends Model
{
    const resp_slug = 'resp_slug';
    const response_text = 'response_text';
    
    const table       = 'social_response_types';

    protected $table = SocialResponseType::table;
    protected $fillable = [SocialResponseType::resp_slug,SocialResponseType::response_text];
}
