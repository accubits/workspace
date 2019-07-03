<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;

class CRMLeadAttachment extends Model
{
    const org_id = "org_id";
    const crm_lead_id = "crm_lead_id";
    const attachment_path = "attachment_path";
    const creator_user_id = 'creator_user_id';

    const table = 'crm_lead_attachments';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = CRMLeadAttachment::table;
    protected $fillable = [CRMLeadAttachment::org_id, CRMLeadAttachment::crm_lead_id, CRMLeadAttachment::attachment_path];
}
