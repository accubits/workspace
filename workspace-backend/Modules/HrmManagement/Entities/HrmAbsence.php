<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmAbsence extends Model
{
    const slug             = 'absence_slug';
    const org_id           = 'org_id';
    const user_id          = 'user_id';
    const absent_start_date_time   = 'absent_start_date_time';
    const absent_end_date_time     = 'absent_end_date_time';
    const is_starts_on_halfday     = 'is_starts_on_halfday';
    const is_ends_on_halfday       = 'is_ends_on_halfday';
    const leave_type_id    = 'leave_type_id';
    const reason           = 'reason';
    const is_halfday           = 'is_halfday';


    const table     = 'hrm_absence';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmAbsence::table;

    protected $fillable = [
        HrmAbsence::slug,
        HrmAbsence::org_id,
        HrmAbsence::user_id,
        HrmAbsence::absent_start_date_time,
        HrmAbsence::absent_end_date_time,
        HrmAbsence::is_starts_on_halfday,
        HrmAbsence::is_ends_on_halfday,
        HrmAbsence::leave_type_id,
        HrmAbsence::reason
    ];
}
