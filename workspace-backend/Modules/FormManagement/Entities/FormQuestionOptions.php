<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormQuestionOptions extends Model
{
    
    const table = "form_question_options";
    const form_question_id = "form_question_id";    
    const fqo_sort_no = "fqo_sort_no";
    const option_text = "option_text";
    const max_quantity = "max_quantity";


    
    protected $table = FormQuestionOptions::table;
    protected $fillable = [
        FormQuestionOptions::form_question_id,
        FormQuestionOptions::fqo_sort_no,
        FormQuestionOptions::option_text,
        FormQuestionOptions::max_quantity
        ];
}
