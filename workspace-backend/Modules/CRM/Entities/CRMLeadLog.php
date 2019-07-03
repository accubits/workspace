<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;

class CRMLeadLog extends Model
{
    const org_id = "org_id";
    const crm_lead_id = "crm_lead_id";
    const log_date = "log_date";
    const description = "description";
    const creator_user_id = 'creator_user_id';

    const table = 'crm_lead_log';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = CRMLeadLog::table;
    protected $fillable = [];
}
