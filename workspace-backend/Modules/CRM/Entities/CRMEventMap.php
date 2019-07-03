<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;

class CRMEventMap extends Model
{
    const org_id = "org_id";
    const crm_lead_id = "crm_lead_id";
    const event_id = "event_id";
    const creator_user_id = 'creator_user_id';

    const table = 'crm_event_map';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = CRMEventMap::table;
    protected $fillable = [];
}
