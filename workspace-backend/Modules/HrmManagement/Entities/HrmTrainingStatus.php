<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmTrainingStatus extends Model
{
    const name     = 'name';
    const value     = 'value';
    
    

    const table     = 'hrm_training_status';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmTrainingStatus::table;
    protected $fillable = [
        HrmTrainingStatus::name,
        HrmTrainingStatus::value
        ];
}
