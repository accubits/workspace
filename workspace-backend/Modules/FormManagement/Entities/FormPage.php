<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormPage extends Model
{
    const table = "form_page";
    
    const form_page_slug = "form_page_slug";
    const form_master_id = "form_master_id";
    const form_components_id = "form_components_id"; 
    const page_title = "page_title";


    protected $table = FormPage::table;    
    protected $fillable = [
        FormPage::form_master_id,
        FormPage::form_components_id,
        FormPage::page_title
        ];
}
