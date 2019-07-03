<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormAnswerAddress extends Model
{
    const table = "form_answer_address";
    
    const form_answers_id = "form_answers_id";
    const street_address = "street_address";
    const address_line2 = "address_line2";
    const city = "city";
    const state = "state";
    const country_id = "country_id";
    const zip_code = "zip_code";
    
    
    protected $table = FormAnswerAddress::table; 
    
    protected $fillable = [
        FormAnswerAddress::form_answers_id,
        FormAnswerAddress::street_address,
        FormAnswerAddress::address_line2,
        FormAnswerAddress::city,
        FormAnswerAddress::state,
        FormAnswerAddress::country_id,
        FormAnswerAddress::zip_code
        ];
}
