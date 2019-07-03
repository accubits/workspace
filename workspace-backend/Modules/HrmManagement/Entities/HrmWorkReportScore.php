<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmWorkReportScore extends Model
{
    const slug           = 'score_slug';
    const display_name   = 'score_display_name';
    const name           = 'score_name';

    const excellence     = 'excellence';
    const positive       = 'positive';
    const negative       = 'negative';


    const table     = 'hrm_work_report_score';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmWorkReportScore::table;

    protected $fillable = [
    ];
}
