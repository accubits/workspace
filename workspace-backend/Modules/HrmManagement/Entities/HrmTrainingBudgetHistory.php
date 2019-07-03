<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmTrainingBudgetHistory extends Model
{
    const org_id= "org_id";
    const training_budget_id = "training_budget_id";
    const training_request_id = "training_request_id";
    const old_balance = "old_balance";
    const new_balance = "new_balance";
    const creator_user_id = "creator_user_id";

    const table     = 'hrm_training_budget_histories';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmTrainingBudgetHistory::table;
    protected $fillable = [];
}
