<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmAppraisalMainModule extends Model
{

    const module_name = "module_name";
    const module_display_name = "module_display_name";
    
    const table       = 'hrm_appraisal_main_modules';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $fillable = [];
}
