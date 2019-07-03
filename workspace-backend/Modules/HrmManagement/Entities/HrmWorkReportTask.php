<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmWorkReportTask extends Model
{
    const task_id          = 'task_id';
    const work_report_id   = 'work_report_id';


    const table     = 'hrm_work_report_task_map';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmWorkReportTask::table;

    protected $fillable = [
        HrmWorkReportTask::task_id,
        HrmWorkReportTask::work_report_id
    ];
}
