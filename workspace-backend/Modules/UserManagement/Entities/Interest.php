<?php

namespace Modules\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    const table = 'um_interest';
    
    const interest_title  = 'title';
    protected $table = Interest::table;

    protected $fillable = [
                            Interest::interest_title,
                            ];
}
