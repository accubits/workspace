<?php

namespace Modules\DriveManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class DrivePermissions extends Model
{
    const slug             = 'drive_permission_slug';
    const name  = 'drive_permission_name';
    const type  = 'drive_permission_type';

    const can_view   = 'VIEW';
    const can_update = 'UPDATE';

    const table     = 'dm_drive_permissions';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = DrivePermissions::table;

    protected $fillable = [
        DrivePermissions::slug,
        DrivePermissions::name,
        DrivePermissions::type
    ];
}
