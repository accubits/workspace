<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormQnsLikertStatement extends Model
{
    const table = "form_qns_likert_statement";
    const form_question_id = "form_question_id";
    const likert_statement = "likert_statement";

    protected $table = FormQnsLikertStatement::table;
    protected $fillable = [
        FormQnsLikertStatement::form_question_id,
        FormQnsLikertStatement::likert_statement
        ];
}
