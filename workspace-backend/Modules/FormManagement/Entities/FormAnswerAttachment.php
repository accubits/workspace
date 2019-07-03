<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormAnswerAttachment extends Model
{
    protected $fillable = ["form_answers_id","answer_attachment"];
}
