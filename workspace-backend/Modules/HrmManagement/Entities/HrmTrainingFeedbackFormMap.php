<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmTrainingFeedbackFormMap extends Model
{
    const org_id = 'org_id';
    const hrm_training_request_id = 'hrm_training_request_id';
    const post_training_form_master_id = 'post_training_form_master_id';
    const post_course_form_master_id = 'post_course_form_master_id';

    const table     = 'hrm_training_feedback_form_maps';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmTrainingFeedbackFormMap::table;    
    protected $fillable = [];
}
