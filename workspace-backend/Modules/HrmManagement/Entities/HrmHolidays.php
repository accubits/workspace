<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmHolidays extends Model
{
    const slug   = 'holiday_slug';
    const org_id = 'org_id';
    const name   = 'holiday_name';
    const description    = 'description';
    const holiday_date   = 'holiday_date';
    const is_restricted  = 'is_restricted';
    const is_repeat_yearly  = 'is_repeat_yearly';
    const creator_id     = 'creator_id';



    const table = 'hrm_holidays';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmHolidays::table;

    protected $fillable = [
        HrmHolidays::slug,
        HrmHolidays::org_id,
        HrmLeaveType::description,
        HrmHolidays::name
    ];
}
