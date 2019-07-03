<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmAppraisalCycleReviewerDepartment extends Model
{
    const org_id = "org_id";
    const appraisal_cycle_id = "appraisal_cycle_id";
    const department_id = "department_id";

    const table = 'hrm_appraisal_cycle_reviewer_departments';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmAppraisalCycleReviewerDepartment::table;
    protected $fillable = [];
}
