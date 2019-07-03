<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmWorkReportEvent extends Model
{
    const event_id          = 'event_id';
    const work_report_id    = 'work_report_id';


    const table     = 'hrm_work_report_event_map';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmWorkReportEvent::table;

    protected $fillable = [
        HrmWorkReportEvent::event_id,
        HrmWorkReportEvent::work_report_id
    ];
}
