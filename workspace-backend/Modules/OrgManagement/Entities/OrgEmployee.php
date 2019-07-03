<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\UserManagement\Entities\User;

class OrgEmployee extends Model
{
    use SoftDeletes;

    const name                = 'employee_name';
    const slug                = 'slug';
    const user_id             = 'user_id';
    const org_id              = 'org_id';
    const joining_date        = 'joining_date';
    const employee_status_id  = 'employee_status_id';
    const org_license_id      = 'org_license_id';
    const org_license_map_id  = 'org_license_map_id';
    const reporting_manager_id   = 'reporting_manager_id';

    const table               = 'org_employee';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = OrgEmployee::table;

    protected $dates = ['deleted_at'];

    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class, OrgEmployee::user_id);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
