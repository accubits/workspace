<?php

namespace Modules\DriveManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class Drive extends Model
{
    const slug           = 'drive_slug';
    const name           = 'drive_name';
    const size           = 'drive_size';
    const drive_type_id  = 'drive_type_id';

    const user_id   = 'user_id';
    const org_id    = 'org_id';

    const table     = 'dm_drive';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = Drive::table;

    protected $fillable = [
        Drive::slug,
        Drive::name,
        Drive::size
    ];
}
