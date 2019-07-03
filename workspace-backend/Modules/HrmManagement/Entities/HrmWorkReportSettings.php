<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmWorkReportSettings extends Model
{
    const slug              = 'settings_slug';
    const org_id            = 'org_id';
    const user_id           = 'user_id';
    const department_id        = 'department_id';
    const report_frequency_id  = 'report_frequency_id';
    const monthly_report_day   = 'monthly_report_day';
    const weekly_report_day    = 'weekly_report_day';
    const start_date           = 'start_date';
    const end_date             = 'end_date';


    const table     = 'hrm_work_report_settings';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmWorkReportSettings::table;

    protected $fillable = [
        HrmWorkReportSettings::org_id,
        HrmWorkReportSettings::report_frequency_id
    ];
}
