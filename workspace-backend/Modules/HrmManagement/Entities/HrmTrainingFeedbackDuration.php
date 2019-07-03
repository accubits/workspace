<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmTrainingFeedbackDuration extends Model
{
    const org_id= "org_id";
    const duration_in_days = "duration_in_days";

    const table     = 'hrm_training_feedback_duration';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmTrainingFeedbackDuration::table;

    protected $fillable = [];
}
