<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmAppraisalCycleApplicable extends Model
{
    const applicable_type = "applicable_type";
    const applicable_type_display_name = "applicable_type_display_name";
    const table       = 'hrm_appraisal_cycle_applicables';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmAppraisalCycleApplicable::table;

    protected $fillable = [HrmAppraisalCycleApplicable::applicable_type, HrmAppraisalCycleApplicable::applicable_type_display_name];
}
