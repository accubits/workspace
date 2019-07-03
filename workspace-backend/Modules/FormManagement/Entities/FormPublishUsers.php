<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormPublishUsers extends Model
{
    const table = "form_master_publish_users";

    const org_id = "org_id";
    const user_id = "user_id";
    const form_master_id = "form_master_id";
    const has_submitted = "has_submitted";
    const creator_id = "creator_id";
    
    protected $table = FormPublishUsers::table;  
    protected $fillable = [
        FormPublishUsers::form_master_id,
        FormPublishUsers::user_id,
        FormPublishUsers::creator_id];
}
