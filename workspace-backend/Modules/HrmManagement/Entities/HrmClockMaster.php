<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Common\Utilities\Utilities;

class HrmClockMaster extends Model
{
    const slug            = 'master_slug';
    const org_id          = 'org_id';
    const user_id         = 'user_id';
    const start_date      = 'start_date';
    const stop_date       = 'stop_date';
    const total_break_time  = 'total_break_time';
    const total_working_time  = 'total_working_time';
    const last_recorded_time  = 'last_recorded_time';
    const note                 = 'note';


    const table     = 'hrm_clock_master';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmClockMaster::table;

    protected $fillable = [
        HrmClockMaster::slug,
        HrmClockMaster::org_id,
        HrmClockMaster::user_id,
        HrmClockMaster::start_date,
        HrmClockMaster::stop_date,
        HrmClockMaster::last_recorded_time,
        HrmClockMaster::note
    ];

    public function setStartDateAttribute($value)
    {
        $this->attributes[HrmClockMaster::start_date] = ($value) ? Utilities::createDateTimeFromUtc($value) : NULL;
    }

    public function setStopDateAttribute($value)
    {
        $this->attributes[HrmClockMaster::stop_date] = ($value) ? Utilities::createDateTimeFromUtc($value) : NULL;
    }
}
