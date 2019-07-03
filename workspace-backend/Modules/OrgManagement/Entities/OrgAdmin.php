<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class OrgAdmin extends Model
{
    const org_id   = 'org_id';
    const user_id   = 'user_id';
    const is_active   = 'is_active';

    const table       = 'org_admins';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = OrgAdmin::table;

    protected $fillable = [OrgAdmin::org_id, OrgAdmin::user_id];
}
