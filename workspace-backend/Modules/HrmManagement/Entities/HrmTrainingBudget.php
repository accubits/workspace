<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmTrainingBudget extends Model
{
    const org_id= "org_id";
    const current_balance = "current_balance";
    const total_balance = "total_balance";
    const creator_user_id = "creator_user_id";
    const last_updated_user_id = "last_updated_user_id";


    const table     = 'hrm_training_budget';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmTrainingBudget::table;
    protected $fillable = [];
}
