<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmWorkReportFrequency extends Model
{
    const slug           = 'frequency_slug';
    const frequency_display_name   = 'frequency_display_name';
    const frequency_name           = 'frequency_name';

    const daily    = 'daily';
    const monthly  = 'monthly';
    const weekly   = 'weekly';



    const table     = 'hrm_work_report_frequency';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmWorkReportFrequency::table;

    protected $fillable = [
        HrmWorkReportFrequency::slug,
        HrmWorkReportFrequency::frequency_display_name,
        HrmWorkReportFrequency::frequency_name
    ];

    public $timestamps = false;
}
