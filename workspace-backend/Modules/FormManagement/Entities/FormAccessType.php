<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormAccessType extends Model
{
    const table ="form_access_types";
    const name = "name";
    protected $table=FormAccessType::table;
    protected $fillable = [FormAccessType::name];
}
