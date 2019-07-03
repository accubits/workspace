<?php

namespace Modules\DriveManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class DrivePermissionUser extends Model
{
    const slug     = 'drive_permission_user_slug';
    const user_id  = 'user_id';
    const shared_by = 'shared_by_id';
    const drive_permission_id  = 'drive_permission_id';
    const drive_content_id  = 'drive_content_id';

    const table     = 'drive_permission_user';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = DrivePermissionUser::table;

    protected $fillable = [
        DrivePermissionUser::user_id,
        DrivePermissionUser::drive_permission_id,
        DrivePermissionUser::drive_content_id
    ];
}
