<?php

namespace Modules\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Permissions extends Permission
{
    const table = 'um_permissions';

    const name          = 'name';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = Permissions::table;

    const FULL_PERMISSION     = 'FULL_PERMISSION';
    const SUBADMIN_PERMISSION = 'SUBADMIN_PERMISSION';
    const PARTNER_PERMISSION          = 'PARTNER_PERMISSION';
    const PARTNER_MANAGER_PERMISSION  = 'PARTNER_MANAGER_PERMISSION';
    const ORG_EMPLOYEE_PERMISSION     = 'ORG_EMPLOYEE_PERMISSION';
    const ORG_EMPLOYEE_PERMISSION_CREATE   = 'ORG_EMPLOYEE_PERMISSION_CREATE';
    const ORG_EMPLOYEE_PERMISSION_EDIT     = 'ORG_EMPLOYEE_PERMISSION_EDIT';
    const ORG_EMPLOYEE_PERMISSION_DELETE   = 'ORG_EMPLOYEE_PERMISSION_DELETE';

    //subadmin permissions
    const SUBADMIN_COMM_WLPOSTS_CREATE   = 'SUBADMIN_COMM_WLPOSTS_CREATE';
    const SUBADMIN_COMM_WLPOSTS_READ     = 'SUBADMIN_COMM_WLPOSTS_READ';
    const SUBADMIN_COMM_WLPOSTS_EDIT     = 'SUBADMIN_COMM_WLPOSTS_EDIT';
    const SUBADMIN_COMM_WLPOSTS_DELETE   = 'SUBADMIN_COMM_WLPOSTS_DELETE';

    const SUBADMIN_COMM_VLPOSTS_CREATE    = 'SUBADMIN_COMM_VLPOSTS_CREATE';
    const SUBADMIN_COMM_VLPOSTS_READ      = 'SUBADMIN_COMM_VLPOSTS_READ';
    const SUBADMIN_COMM_VLPOSTS_EDIT      = 'SUBADMIN_COMM_VLPOSTS_EDIT';
    const SUBADMIN_COMM_VLPOSTS_DELETE    = 'SUBADMIN_COMM_VLPOSTS_DELETE';

}
