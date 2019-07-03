<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialEvent extends Model
{
    const event_slug                 = 'event_slug';
    const event_title                = 'event_title';
    const description                = 'event_description';
    const event_start_date           = 'event_start_date';
    const event_end_date             = 'event_end_date';
    const org_id                     = 'org_id';
    const creator_user_id            = 'creator_user_id';
    const is_allday                  = 'is_allday';
    const reminder_count             = 'reminder_count';
    const reminder_type_id           = 'reminder_type_id';
    const reminder_datetime          = 'reminder_datetime';
    const location                   = 'location';
    const is_event_to_all            = 'is_event_to_all';
    const is_reminder_sent           = 'is_reminder_sent';
    const availabilty_lookup_id      = 'availabilty_lookup_id';
    const repeat_lookup_id           = 'repeat_lookup_id';
    const importance_lookup_id       = 'importance_lookup_id';
    const user_calender_id           = 'user_calender_id';

    const table                      = 'social_event';

    protected $table     =  SocialEvent::table;




    protected $fillable = [SocialEvent::event_title, 
                           SocialEvent::description,
                           SocialEvent::event_start_date,
                           SocialEvent::event_end_date,
                           SocialEvent::is_allday ,
                           SocialEvent::reminder_datetime,
                           SocialEvent::location ,
                           SocialEvent::is_event_to_all
                           ];
}
