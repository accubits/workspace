<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmAppraisalForEmployee extends Model
{
    const org_id = "org_id";
    const appraisal_cycle_id = "appraisal_cycle_id";
    const employee_id = "employee_id";

    const table = 'hrm_appraisal_for_employees';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmAppraisalForEmployee::table;
    protected $fillable = [];
}
