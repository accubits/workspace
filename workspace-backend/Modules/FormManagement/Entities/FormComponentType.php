<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormComponentType extends Model
{
    const table = "form_component_type";
    const cmp_type_name = "cmp_type_name";
    const cmp_type_displayname = "cmp_type_displayname"; 
    const is_active = "is_active";

    protected $table = FormComponentType::table;
    protected $fillable = [FormComponentType::cmp_type_displayname, FormComponentType::cmp_type_name, FormComponentType::is_active];
}
