<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormAnswerSheet extends Model
{
    const table = "form_answer_sheet";
    
    const form_master_id = "form_master_id";
    const slug = "slug";
    const submit_datetime = "submit_datetime";
    const is_discarded = "is_discarded";
    const form_user_id = "form_user_id";
    
    protected $table = FormAnswerSheet::table;
    protected $fillable = [
        FormAnswerSheet::form_master_id,
        FormAnswerSheet::slug,
        FormAnswerSheet::submit_datetime,
        FormAnswerSheet::form_user_id
        ];
}
