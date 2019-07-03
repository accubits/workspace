<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormQnsLikertColumns extends Model
{
    const table = "form_qns_likert_columns";
    const form_question_id = "form_question_id";
    const likert_column = "likert_column";

    protected $table = FormQnsLikertColumns::table;
    protected $fillable = [
        FormQnsLikertColumns::form_question_id,
        FormQnsLikertColumns::likert_column
        ];
}
