<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmAcKraModuleWeightage extends Model
{
    const org_id = "org_id";
    const appraisal_cycle_id = "appraisal_cycle_id";
    const kra_module_id = "kra_module_id";
    const score_percent = "score_percent";

    const table = 'hrm_ac_kra_module_weightages';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmAcKraModuleWeightage::table;
    protected $fillable = [
        HrmAcKraModuleWeightage::org_id,
        HrmAcKraModuleWeightage::appraisal_cycle_id,
        HrmAcKraModuleWeightage::kra_module_id,
        HrmAcKraModuleWeightage::score_percent
        ];
}
