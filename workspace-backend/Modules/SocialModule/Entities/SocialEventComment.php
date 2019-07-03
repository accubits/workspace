<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialEventComment extends Model
{
    const slug                       = 'event_comment_slug';
    const description                = 'comment_description';
    const parent_event_comment_id    = 'parent_event_comment_id';
    const  social_event_id           = 'social_event_id';
    const user_id                    = 'user_id';


    const table       = 'social_event_comment';
    
    protected $table     =  SocialEventComment::table;
    protected $fillable = [
        SocialEventComment::social_event_id,
        SocialEventComment::user_id,

    ];
}
