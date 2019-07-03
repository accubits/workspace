<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormPageComponentsMap extends Model
{
    const table = "form_page_components_map";

    const form_master_id = "form_master_id";
    const form_page_id = "form_page_id";
    const form_components_id = "form_components_id";
    const fpc_sort_no = "fpc_sort_no";
    
    protected $table=FormPageComponentsMap::table;
    protected $fillable = [
        FormPageComponentsMap::form_master_id,
        FormPageComponentsMap::form_page_id,
        FormPageComponentsMap::form_components_id,
        FormPageComponentsMap::fpc_sort_no
        ];
}
