<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmTrainingFeedbackResponse extends Model
{
    
    const org_id = 'org_id';
    const training_request_id = 'training_request_id';
    const form_master_id = 'form_master_id';
    const employee_id = 'employee_id';
    const form_answersheet_id = 'form_answersheet_id';
    const score = 'score';
    const is_final = 'is_final';

    const table     = 'hrm_training_feedback_responses';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmTrainingFeedbackResponse::table;  
    protected $fillable = [];
}
