<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormComponents extends Model
{
    const table = "form_components";
    const form_master_id = "form_master_id";
    const fc_sort_no = "fc_sort_no";
    const form_component_type_id = "form_component_type_id";

    protected $table = FormComponents::table;
    protected $fillable = [
        FormComponents::form_master_id,
        FormComponents::fc_sort_no,
        FormComponents::form_component_type_id
        ];
}
