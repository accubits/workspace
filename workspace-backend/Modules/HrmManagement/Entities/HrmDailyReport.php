<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmDailyReport extends Model
{
    const slug            = 'daily_report_slug';
    const clock_master_id = 'clock_master_id';
    const creator_id      = 'creator_id';
    const supervisor_id   = 'supervisor_id';



    const table     = 'hrm_daily_report';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmDailyReport::table;

    protected $fillable = [
        HrmDailyReport::slug,
        HrmDailyReport::clock_master_id,
        HrmDailyReport::creator_id,
        HrmDailyReport::supervisor_id
    ];
}
