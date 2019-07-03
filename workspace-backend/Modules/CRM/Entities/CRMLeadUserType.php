<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;

class CRMLeadUserType extends Model
{
    const type_name = 'type_name';
    const type_displayname = 'type_displayname';

    const table = 'crm_lead_user_type';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = CRMLeadUserType::table;
    protected $fillable = [CRMLeadUserType::type_name, CRMLeadUserType::type_displayname];
}
