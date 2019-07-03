<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;

class CRMTaskMap extends Model
{
    const org_id = "org_id";
    const crm_lead_id = "crm_lead_id";
    const task_id = "task_id";
    const creator_user_id = 'creator_user_id';

    const table = 'crm_task_map';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = CRMTaskMap::table;
    protected $fillable = [];
}
