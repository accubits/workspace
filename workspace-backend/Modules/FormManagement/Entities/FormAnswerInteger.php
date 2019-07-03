<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormAnswerInteger extends Model
{
    const table = "form_answer_integer";
    
    const form_answers_id = "form_answers_id";
    const answer_integer = "answer_integer";
    
    protected $table = FormAnswerInteger::table;
    protected $fillable = [
        FormAnswerInteger::form_answers_id,
        FormAnswerInteger::answer_integer
        ];
}
