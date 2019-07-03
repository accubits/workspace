<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormQuestion extends Model
{
    const table = "form_question";
    const form_components_id = "form_components_id";    
    const form_question_text = "form_question_text";
    const is_mandatory = "is_mandatory";
    const has_unique_answer = "has_unique_answer";
    const randomize_answeroption = "randomize_answeroption";
    const allow_otheroption = "allow_otheroption";
    const min_range = "min_range";
    const max_range = "max_range";
    const currency_type = "currency_type";

    protected $table = FormQuestion::table;
    protected $fillable = [
        FormQuestion::form_components_id,
        FormQuestion::form_question_text,
        FormQuestion::is_mandatory,
        FormQuestion::has_unique_answer,
        FormQuestion::randomize_answeroption,
        FormQuestion::allow_otheroption,
        FormQuestion::min_range,
        FormQuestion::max_range
        ];
}
