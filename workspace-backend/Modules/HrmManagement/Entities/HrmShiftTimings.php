<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmShiftTimings extends Model
{
    const slug         = 'timing_slug';
    const start_time   = 'start_time';
    const end_time     = 'end_time';

    const table     = 'hrm_shift_timings';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmShiftTimings::table;

    protected $fillable = [
        HrmShiftTimings::slug,
        HrmShiftTimings::start_time,
        HrmShiftTimings::end_time
    ];
}
