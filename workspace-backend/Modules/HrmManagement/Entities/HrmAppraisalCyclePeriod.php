<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmAppraisalCyclePeriod extends Model
{
    const period_type = "period_type";
    const period_type_display_name = "period_type_display_name";
    const table       = 'hrm_appraisal_cycle_periods';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmAppraisalCyclePeriod::table;    
    protected $fillable = [HrmAppraisalCyclePeriod::period_type, HrmAppraisalCyclePeriod::period_type_display_name];
}
