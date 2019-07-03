<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormMasterUsers extends Model
{
    const table = "form_master_users";

    const user_id = "user_id";
    const form_master_id = "form_master_id";
    const form_permission = "form_permission";
    const creator_id = "creator_id";
    
    protected $fillable = [
        FormMasterUsers::user_id,
        FormMasterUsers::form_master_id,
        FormMasterUsers::form_permission,
        FormMasterUsers::creator_id];
}
