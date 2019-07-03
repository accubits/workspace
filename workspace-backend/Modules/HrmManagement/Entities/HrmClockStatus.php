<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmClockStatus extends Model
{
    const slug           = 'status_slug';
    const display_name   = 'status_display_name';
    const name           = 'status_name';


    const clockin         = 'clockIn';
    const clockout        = 'clockOut';
    const pause           = 'pause';
    const clockContinue   = 'clockContinue';
    const clockResume     = 'clockResume';
    const earlyClockout   = 'earlyClockout';


    const table     = 'hrm_clock_status';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmClockStatus::table;

    protected $fillable = [
        HrmClockStatus::slug,
        HrmClockStatus::name,
        HrmClockStatus::display_name
    ];
}
