<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;


class FormMaster extends Model {

    const table = "form_master";
    
    const form_slug = "form_slug";
    const form_title = "form_title";
    const description = "description";
    const form_access_type_id = "form_access_type_id";
    const form_status_id = "form_status_id";
    const is_template = "is_template"; 
    const is_archived = "is_archived";
    const is_published = "is_published";
    const allow_multi_submit = "allow_multi_submit";
    const creator_user_id = "creator_user_id";

    protected $table = FormMaster::table;
    protected $fillable = [
        FormMaster::form_slug,
        FormMaster::form_title,
        FormMaster::description,
        FormMaster::form_access_type_id,
        FormMaster::form_status_id,
        FormMaster::is_archived,
        FormMaster::is_published,
        FormMaster::creator_user_id
    ];

}
