<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmAppraisalCycleReviewerEmployee extends Model
{
    const org_id = "org_id";
    const appraisal_cycle_id = "appraisal_cycle_id";
    const employee_id = "employee_id";

    const table = 'hrm_appraisal_cycle_reviewer_employees';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmAppraisalCycleReviewerEmployee::table;
    protected $fillable = [];
}
