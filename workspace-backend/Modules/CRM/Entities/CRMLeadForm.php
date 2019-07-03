<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;

class CRMLeadForm extends Model
{
    const org_id = "org_id";
    const form_master_id = "form_master_id";
    const addedby_user_id = 'addedby_user_id';

    const table = 'crm_lead_form';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = CRMLeadForm::table;
    protected $fillable = [];
}
