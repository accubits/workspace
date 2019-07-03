<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Common\Utilities\Utilities;

class HrmClock extends Model
{
    const slug             = 'clock_slug';
    const org_id           = 'org_id';
    const user_id          = 'user_id';
    const clock_datetime   = 'clock_datetime';
    const clock_status_id  = 'clock_status_id';
    const clock_master_id  = 'clock_master_id';


    const table     = 'hrm_clock';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmClock::table;

    protected $fillable = [
        HrmClock::slug,
        HrmClock::org_id,
        HrmClock::user_id,
        HrmClock::clock_datetime,
        HrmClock::clock_status_id,
        HrmClock::clock_master_id
    ];

    public function setClockDatetimeAttribute($value)
    {
        $this->attributes[HrmClock::clock_datetime] = ($value) ? Utilities::createDateTimeFromUtc($value) : NULL;
    }
}
