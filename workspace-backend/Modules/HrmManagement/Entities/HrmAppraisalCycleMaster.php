<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmAppraisalCycleMaster extends Model
{
    const slug = "slug";
    const org_id = "org_id";
    const name = "name";
    const description = "description";
    const appraisal_cycle_period_id = "appraisal_cycle_period_id";
    const cycle_start_date = "cycle_start_date";
    const cycle_end_date = "cycle_end_date";
    const processing_start_date = "processing_start_date";
    const processing_end_date = "processing_end_date";
    const applicable_id = "applicable_id";
    const review_by_self = "review_by_self";
    const review_by_department_head = "review_by_department_head";
    const review_by_employee = "review_by_employee";

    const creator_user_id    = 'creator_user_id';
    
    const table       = 'hrm_appraisal_cycle_master';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmAppraisalCycleMaster::table;
    protected $fillable = [HrmAppraisalCycleMaster::slug,HrmAppraisalCycleMaster::name];
}
