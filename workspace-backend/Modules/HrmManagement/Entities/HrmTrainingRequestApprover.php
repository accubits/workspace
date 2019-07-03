<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class HrmTrainingRequestApprover extends Model
{
    const training_request_id     = 'training_request_id';
    const employee_id     = 'employee_id';
    const has_approved = 'has_approved';

    const table     = 'hrm_training_request_approvers';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmTrainingRequestApprover::table;    
    protected $fillable = [];
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where(HrmTrainingRequestApprover::training_request_id, '=', $this->getAttribute(HrmTrainingRequestApprover::training_request_id))
            ->where(HrmTrainingRequestApprover::employee_id, '=', $this->getAttribute(HrmTrainingRequestApprover::employee_id));
        return $query;
    }
}
