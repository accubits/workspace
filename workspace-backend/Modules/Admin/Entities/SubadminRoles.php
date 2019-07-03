<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class SubadminRoles extends Model
{
    const table = 'subadmin_roles';

    const name    = 'name';
    const role_id = 'role_id';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SubadminRoles::table;


    const PARTNER_MANAGER         = 'PARTNER MANAGER';
    const COMMUNICATION_MANAGER   = 'COMMUNICATION MANAGER';
    const LICENSE_MANAGER         = 'LICENSE MANAGER';
    const WORKFLOW_MANAGER        = 'WORKFLOW MANAGER';
    const FORM_MANAGER            = 'FORM MANAGER';
    const PERMISSIONS_MANAGER     = 'PERMISSIONS MANAGER';
}
