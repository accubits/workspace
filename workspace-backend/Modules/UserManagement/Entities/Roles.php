<?php

namespace Modules\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Roles extends Role
{
    const table = 'um_roles';

    const name = 'name';
    const guard_name = "guard_name";
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = Roles::table;

    const SUPER_ADMIN = 'SUPER_ADMIN';
    const SUB_ADMIN   = 'SUB_ADMIN';
    const PARTNER_MANAGER = 'PARTNER_MANAGER';
    const PARTNER         = 'PARTNER';
    const ORG_EMPLOYEE    = 'ORG_EMPLOYEE';
    const ORG_GROUP_EMPLOYEE    = 'ORG_GROUP_EMPLOYEE';
    const ORG_ADMIN    = 'ORG_ADMIN';
    const COMMUNICATION_MANAGER   = 'COMMUNICATION_MANAGER';
    const LICENSE_MANAGER         = 'LICENSE_MANAGER';
    const WORKFLOW_MANAGER        = 'WORKFLOW_MANAGER';
    const FORM_MANAGER            = 'FORM_MANAGER';
    const PERMISSIONS_MANAGER     = 'PERMISSIONS_MANAGER';
}
