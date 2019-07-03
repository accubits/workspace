<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormStatus extends Model
{
    const table ="form_status";
    const status_name="status_name";

    protected $table=FormStatus::table;
    protected $fillable = [FormStatus::status_name];
}
