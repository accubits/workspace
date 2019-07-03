<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmTrainingRequest extends Model
{
    const slug         = 'slug';
    const org_id   = 'org_id';
    const name     = 'name';
    const details     = 'details';
    const starts_on     = 'starts_on';
    const ends_on     = 'ends_on';
    const cost     = 'cost';
    const from_employee_id     = 'from_employee_id';
    const status_id     = 'status_id';
    const in_progress     = 'in_progress';
    const is_cancelled     = 'is_cancelled';
    const is_completed     = 'is_completed';
    const has_feedback_form     = 'has_feedback_form';
    
    

    const table     = 'hrm_training_requests';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmTrainingRequest::table;
    protected $fillable = [
        HrmTrainingRequest::slug,
        HrmTrainingRequest::org_id,
        HrmTrainingRequest::name,
        HrmTrainingRequest::details,
        HrmTrainingRequest::starts_on,
        HrmTrainingRequest::ends_on,
        HrmTrainingRequest::cost,
        HrmTrainingRequest::from_employee_id,
        HrmTrainingRequest::status_id,
        HrmTrainingRequest::is_completed,
        HrmTrainingRequest::has_feedback_form
            ];
}
