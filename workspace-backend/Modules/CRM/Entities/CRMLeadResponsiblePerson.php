<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;

class CRMLeadResponsiblePerson extends Model
{
    
    const org_id = "org_id";
    const crm_lead_id = "crm_lead_id";
    const attachment_path = "attachment_path";
    const crm_employee_id = "crm_employee_id";
    const addedby_user_id = 'addedby_user_id';

    const table = 'crm_lead_responsible_people';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = CRMLeadResponsiblePerson::table;
    protected $fillable = [];
}
