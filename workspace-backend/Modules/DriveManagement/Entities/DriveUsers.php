<?php

namespace Modules\DriveManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class DriveUsers extends Model
{
    const user_id      = 'user_id';
    const drive_content_id      = 'drive_content_id';

    const table     = 'dm_drive_users';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = DriveUsers::table;

    protected $fillable = [

    ];
}
