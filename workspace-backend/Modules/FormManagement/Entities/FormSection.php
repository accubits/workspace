<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormSection extends Model
{
    const table = "form_section";
    
    const form_components_id = "form_components_id"; 
    const form_page_id = "form_page_id";
    const fs_title = "fs_title";
    const fs_desc = "fs_desc";


    protected $table = FormSection::table;    
    protected $fillable = [
        FormSection::form_components_id,
        FormSection::form_page_id, 
        FormSection::fs_title,
        FormSection::fs_desc
        ];
}
