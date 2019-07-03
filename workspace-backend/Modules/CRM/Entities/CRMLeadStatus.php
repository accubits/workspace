<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;

class CRMLeadStatus extends Model
{
    
    const status_name         = 'status_name';
    const status_displayname   = 'status_displayname';

    const table       = 'crm_lead_status';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = CRMLeadStatus::table;
    protected $fillable = [CRMLeadStatus::status_name, CRMLeadStatus::status_displayname];
}
