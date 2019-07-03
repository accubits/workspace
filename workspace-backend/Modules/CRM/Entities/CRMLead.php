<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;

class CRMLead extends Model
{
    const slug = "slug";
    const org_id = "org_id";
    const name = "name";
    const lead_user_type_id = "lead_user_type_id";
    const date_of_birth = "date_of_birth";
    const email = "email";
    const phone = "phone";
    const lead_status_id = "lead_status_id";
    const image_path = "image_path";
    const creator_user_id = 'creator_user_id';

    const table = 'crm_lead';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = CRMLead::table;
    protected $fillable = [CRMLead::org_id, CRMLead::name];
}
