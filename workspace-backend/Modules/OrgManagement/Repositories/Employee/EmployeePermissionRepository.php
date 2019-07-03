<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 7/2/18
 * Time: 12:04 PM
 */

namespace Modules\OrgManagement\Repositories\Employee;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Repositories\EmployeePermissionRepositoryInterface;
use Modules\UserManagement\Entities\Permissions;
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\User;

class EmployeePermissionRepository implements EmployeePermissionRepositoryInterface
{

    protected $employee;
    protected $content;


    public function __construct()
    {
        $this->content = array();
    }

    public function throwError($data, $code) : array
    {
        $this->content['error'] = $data;
        $this->content['code']  = $code;
        return $this->content;
    }

    public function getOrganizationEmployee($slug) : ?OrgEmployee
    {
        return OrgEmployee::where(OrgEmployee::slug, $slug)->first();
    }

    public function getEmployeeAllPermissions(OrgEmployee $orgEmployee) : ?array
    {
        return $orgEmployee->user->getAllPermissions()->map(function ($name) {
            return $name->name;
        })->all();
    }

    /**
     * list employee permissions
     * @param $slug
     * @return array
     */
    public function getEmployeePermissions($slug)
    {
        $orgEmployee = $this->getOrganizationEmployee($slug);

        if (!$orgEmployee)
            return $this->throwError('No Employee Found', 422);

        $permissions = $this->getEmployeeAllPermissions($orgEmployee);

        $this->content['data']   =  $permissions;
        $this->content['code']   =  200;
        return $this->content;

    }

    public function getPermissionsFromRoles()
    {
        $role = Roles::where(Roles::name, Roles::ORG_GROUP_EMPLOYEE)->firstOrFail();
        $permissions = DB::table('um_role_permissions')->where('role_id', $role->id)->get();

        return $permission = $permissions->map(function ($name) {
            $permission = DB::table(Permissions::table)->select(Permissions::name)->where('id', $name->permission_id)->first();
            return $permission->name;
        })->all();
    }


    public function assignPermission(Request $request)
    {
        $orgEmployee = $this->getOrganizationEmployee($request->employee_slug);

        if (!$orgEmployee)
            return $this->throwError('No Employee Found', 422);

        $userPermissions = $this->getEmployeeAllPermissions($orgEmployee);

        $revokePermission = (array_diff($userPermissions, $request->name));
        if (!empty($revokePermission)) {
            $orgEmployee->user->revokePermissionTo($revokePermission);
        }

        $orgEmployee->user->syncPermissions($request->name);

        $this->content['data']   =  "permission added successfully!";
        $this->content['code']   =  201;
        return $this->content;
    }
}