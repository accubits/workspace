<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Common\Utilities\Utilities;

class HrmClockEditHistory extends Model
{
    const slug                  = 'edit_history_slug';
    const org_id                = 'org_id';
    const clock_master_id       = 'clock_master_id';
    const prev_start_date       = 'prev_start_date';
    const prev_end_date         = 'prev_end_date';
    const prev_break_time       = 'prev_break_time';
    const start_date            = 'start_date';
    const end_date              = 'end_date';
    const break_time            = 'break_time';
    const note                  = 'note';


    const table     = 'hrm_clock_edit_history';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmClockEditHistory::table;

    protected $fillable = [
        HrmClockEditHistory::slug,
        HrmClockEditHistory::org_id,
        HrmClockEditHistory::clock_master_id,
        HrmClockEditHistory::prev_start_date,
        HrmClockEditHistory::prev_end_date,
        HrmClockEditHistory::prev_break_time,
        HrmClockEditHistory::start_date,
        HrmClockEditHistory::end_date,
        HrmClockEditHistory::break_time,
        HrmClockEditHistory::note,
    ];


    public function setStartDateAttribute($value)
    {
        $this->attributes[HrmClockEditHistory::start_date] = ($value) ? Utilities::createDateTimeFromUtc($value) : NULL;
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes[HrmClockEditHistory::end_date] = ($value) ? Utilities::createDateTimeFromUtc($value) : NULL;
    }
}
