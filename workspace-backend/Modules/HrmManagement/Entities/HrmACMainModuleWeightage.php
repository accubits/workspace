<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmACMainModuleWeightage extends Model
{

    const org_id = "org_id";
    const appraisal_cycle_id = "appraisal_cycle_id";
    const appraisal_main_module_id = "appraisal_main_module_id";
    const score_percent = "score_percent";

    const table = 'hrm_ac_main_module_weightages';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmACMainModuleWeightage::table;
    protected $fillable = [
        HrmACMainModuleWeightage::org_id,
        HrmACMainModuleWeightage::appraisal_cycle_id,
        HrmACMainModuleWeightage::appraisal_main_module_id,
        HrmACMainModuleWeightage::score_percent
        ];
}
