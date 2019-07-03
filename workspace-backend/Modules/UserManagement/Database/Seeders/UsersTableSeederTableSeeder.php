<?php

namespace Modules\UserManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\Permissions;
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;
use Spatie\Permission\Models\Role;

class UsersTableSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        if (!User::where('email', 'shamon@accubits.com')->exists()) {
            $user = new User;
            $user->name     = "Shamon Superadmin";
            $user->email    = "shamon@accubits.com";
            $user->password = bcrypt("qwerty@123");
            $user->save();

            $userProfile = new UserProfile([
                UserProfile::first_name => 'Shamon',
                UserProfile::last_name  => 'Superadmin',
                UserProfile::user_id  => $user->id
            ]);

            $user->userProfile()->save($userProfile);
            $user->assignRole(Roles::SUPER_ADMIN);
        }

        if (!User::where('email', 'admin@gmail.com')->exists()) {
            $user = new User;
            $user->name     = "superadmin";
            $user->email    = "admin@gmail.com";
            $user->password = bcrypt("admin@123");
            $user->save();

            $userProfile = new UserProfile([
                UserProfile::first_name => 'superadmin',
                UserProfile::last_name  => 'admin'
            ]);

            $user->userProfile()->save($userProfile);
            $user->assignRole(Roles::SUPER_ADMIN);
        }


        if (!User::where('email', 'system@mailinator.com')->exists()) {
            // System User
            $user = new User;
            $user->name     = "System";
            $user->email    = "system@mailinator.com";
            $user->password = bcrypt("admin@123");
            $user->save();

            $userProfile = new UserProfile([
                UserProfile::first_name => 'System',
                UserProfile::last_name  => 'Log'
            ]);

            $user->userProfile()->save($userProfile);
            $user->assignRole(Roles::SUPER_ADMIN);
        }


        if (!User::where('email', 'partnermanager@gmail.com')->exists()) {
            //Partner Manager
            $user = new User;
            $user->name     = "partner manager";
            $user->email    = "partnermanager@gmail.com";
            $user->password = bcrypt("pm@123");
            $user->save();

            $userProfile = new UserProfile([
                UserProfile::first_name => 'partner',
                UserProfile::last_name  => 'manager'
            ]);

            $user->userProfile()->save($userProfile);
            $user->assignRole(Roles::PARTNER_MANAGER);
        }


        if (!User::where('email', 'partner@gmail.com')->exists()) {
            //Partner
            $user = new User;
            $user->name     = "partner";
            $user->email    = "partner@gmail.com";
            $user->password = bcrypt("p@123456");
            $user->save();

            $userProfile = new UserProfile([
                UserProfile::first_name => 'partner',
                UserProfile::last_name  => ''
            ]);

            $user->userProfile()->save($userProfile);
            $user->assignRole(Roles::PARTNER);
        }

        if (!User::where('email', 'pmliya@mailinator.com')->exists()) {
            // Partner Manager
            $user = new User;
            $user->name     = "Partner Manager Liya";
            $user->email    = "pmliya@mailinator.com";
            $user->password = bcrypt("qwerty@123");
            $user->save();

            $userProfile = new UserProfile([
                UserProfile::first_name => 'Partner Manager',
                UserProfile::last_name  => 'Liya'
            ]);

            $user->userProfile()->save($userProfile);
            $user->assignRole(Roles::PARTNER_MANAGER);
        }

        if (!User::where('email', 'partnerliya@mailinator.com')->exists()) {
            // Partner
            $user = new User;
            $user->name     = "Partner Liya";
            $user->email    = "partnerliya@mailinator.com";
            $user->password = bcrypt("qwerty@123");
            $user->save();

            $userProfile = new UserProfile([
                UserProfile::first_name => 'Partner',
                UserProfile::last_name  => 'Liya'
            ]);

            $user->userProfile()->save($userProfile);
            $user->assignRole(Roles::PARTNER);
        }

        if (!User::where('email', 'partner_reshman@mailinator.com')->exists()) {
            // Partner
            $user = new User;
            $user->name     = "Partner Reshman";
            $user->email    = "partner_reshman@mailinator.com";
            $user->password = bcrypt("qwerty@123");
            $user->save();

            $userProfile = new UserProfile([
                UserProfile::first_name => 'Partner Reshman',
                UserProfile::last_name  => 'Suresh'
            ]);

            $user->userProfile()->save($userProfile);
            $user->assignRole(Roles::PARTNER);
        }

        if (!User::where('email', 'partnermanager_reshman@mailinator.com')->exists()) {
            // Partner Manager
            $user = new User;
            $user->name     = "Partner Manager Reshman";
            $user->email    = "partnermanager_reshman@mailinator.com";
            $user->password = bcrypt("qwerty@123");
            $user->save();

            $userProfile = new UserProfile([
                UserProfile::first_name => 'Partner Manager Reshman',
                UserProfile::last_name  => 'Suresh'
            ]);

            $user->userProfile()->save($userProfile);
            $user->assignRole(Roles::PARTNER_MANAGER);
        }

        if (!User::where('email', 'icosyspartnermanager@mailinator.com')->exists()) {
            // Partner Manager
            $user = new User;
            $user->name     = "Icosys Partner Manager";
            $user->email    = "icosyspartnermanager@mailinator.com";
            $user->password = bcrypt("qwerty@123");
            $user->save();

            $userProfile = new UserProfile([
                UserProfile::first_name => 'Icosys',
                UserProfile::last_name  => 'Partner Manager'
            ]);

            $user->userProfile()->save($userProfile);
            $user->assignRole(Roles::PARTNER_MANAGER);
        }

        if (!User::where('email', 'icosyspartner1@mailinator.com')->exists()) {
            // Partner
            $user = new User;
            $user->name     = "Icosys Partner1";
            $user->email    = "icosyspartner1@mailinator.com";
            $user->password = bcrypt("qwerty@123");
            $user->save();

            $userProfile = new UserProfile([
                UserProfile::first_name => 'Icosys',
                UserProfile::last_name  => 'Partner1'
            ]);

            $user->userProfile()->save($userProfile);
            $user->assignRole(Roles::PARTNER);
        }

        if (!User::where('email', 'icosyspartner2@mailinator.com')->exists()) {
            // Partner
            $user = new User;
            $user->name     = "Icosys Partner2";
            $user->email    = "icosyspartner2@mailinator.com";
            $user->password = bcrypt("qwerty@123");
            $user->save();

            $userProfile = new UserProfile([
                UserProfile::first_name => 'Icosys',
                UserProfile::last_name  => 'Partner2'
            ]);

            $user->userProfile()->save($userProfile);
            $user->assignRole(Roles::PARTNER);
        }
    }
}
