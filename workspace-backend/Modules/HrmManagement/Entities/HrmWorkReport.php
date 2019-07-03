<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmWorkReport extends Model
{
    const slug              = 'report_slug';
    const title             = 'report_title';
    const org_id            = 'org_id';
    const superviser_id     = 'superviser_id';
    const creator_id        = 'creator_id';
    const start_date        = 'start_date';
    const end_date          = 'end_date';
    const report_sent_date  = 'report_sent_date';
    const is_report_sent    = 'is_report_sent';
    const report_score_id   = 'report_score_id';
    const is_confirmed      = 'is_confirmed';


    const table     = 'hrm_work_report';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmWorkReport::table;

    protected $fillable = [
    ];
}
