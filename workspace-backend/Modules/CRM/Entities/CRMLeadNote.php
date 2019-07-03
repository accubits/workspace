<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;

class CRMLeadNote extends Model
{
    const slug = "slug";
    const org_id = "org_id";
    const crm_lead_id = "crm_lead_id";
    const description = "description";
    const creator_user_id = 'creator_user_id';

    const table = 'crm_lead_note';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = CRMLeadNote::table;
    protected $fillable = [];
}
