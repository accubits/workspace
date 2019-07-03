<?php

namespace Modules\UserManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\Permissions;
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\User;

class UserManagementDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Model::unguard();
        /*DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        Roles::truncate();
        Permissions::truncate();*/

        $this->call(RolesDatabaseSeeder::class);
        $this->call(PermissionsDatabaseSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(UsersTableSeederTableSeeder::class);
 
        //DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        // $this->call("OthersTableSeeder");
    }
}
