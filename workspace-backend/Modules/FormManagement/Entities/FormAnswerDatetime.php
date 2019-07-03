<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormAnswerDatetime extends Model
{
    const table = "form_answer_datetime";
    
    const form_answers_id = "form_answers_id";
    const answer_datetime = "answer_datetime";
    
    protected $table = FormAnswerDatetime::table;
    protected $fillable = [
        FormAnswerDatetime::form_answers_id,
        FormAnswerDatetime::answer_datetime
        ];
}
