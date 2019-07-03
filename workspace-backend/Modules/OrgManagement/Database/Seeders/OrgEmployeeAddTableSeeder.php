<?php

namespace Modules\OrgManagement\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Entities\OrgEmployeeStatus;
use Modules\OrgManagement\Entities\OrgLicenseMapping;
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;

class OrgEmployeeAddTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $organization = Organization::where(Organization::name, 'Icosys')->first();
        $organizationMapping = $this->getOrganizationMapping($organization);


        $licenseId      = $organizationMapping->{OrgLicenseMapping::license_id};

        $employeeStatus = OrgEmployeeStatus::where(OrgEmployeeStatus::name, OrgEmployeeStatus::WORKING)->first();

        if (!User::where('email', 'nadia@icosys.com.sg')->exists()) {
            $request = collect([
                'name'     => 'Nadia',
                'email'    => 'nadia@icosys.com.sg',
                'password' => bcrypt('qwerty@123')
            ]);



            $user = $this->createUser($request);
            $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);

            $profile = new UserProfile();
            $profile->{UserProfile::first_name} = $request->get('name');
            $profile->{UserProfile::last_name}  = $request->get('name');
            $profile->{UserProfile::user_id}    = $user->id;
            $profile->{UserProfile::user_image} = 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=200';
            $profile->save();
        } else {
            $user = User::where(User::email, 'nadia@icosys.com.sg')->first();
            if (!empty($user)) {

                $request = collect([
                    'name'     => 'Nadia',
                    'email'    => 'nadia@icosys.com.sg',
                    'password' => bcrypt('qwerty@123')
                ]);

                if (!OrgEmployee::where(OrgEmployee::user_id, $user->id)->where(OrgEmployee::org_id, $organization->id)->exists()) {
                    $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);
                }
            }

        }

        if (!User::where('email', 'barry@mailinator.com')->exists()) {
            $request = collect([
                'name'     => 'Barry',
                'email'    => 'barry@mailinator.com',
                'password' => bcrypt('qwerty@123')
            ]);


            $user = $this->createUser($request);
            $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);

            $profile = new UserProfile();
            $profile->{UserProfile::first_name} = $request->get('name');
            $profile->{UserProfile::last_name}  = $request->get('name');
            $profile->{UserProfile::user_id}    = $user->id;
            $profile->{UserProfile::user_image} = 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=200';
            $profile->save();
        } else {
            $user = User::where(User::email, 'barry@mailinator.com')->first();
            if (!empty($user)) {

                $request = collect([
                    'name'     => 'Barry',
                    'email'    => 'barry@mailinator.com',
                    'password' => bcrypt('qwerty@123')
                ]);

                if (!OrgEmployee::where(OrgEmployee::user_id, $user->id)->where(OrgEmployee::org_id, $organization->id)->exists()) {
                    $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);
                }
            }

        }

        if (!User::where('email', 'arunvj@accubits.com')->exists()) {
            $request = collect([
                'name'     => 'Arun',
                'email'    => 'arunvj@accubits.com',
                'password' => bcrypt('qwerty@123')
            ]);


            $user = $this->createUser($request);
            $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);

            $profile = new UserProfile();
            $profile->{UserProfile::first_name} = $request->get('name');
            $profile->{UserProfile::last_name}  = $request->get('name');
            $profile->{UserProfile::user_id}    = $user->id;
            $profile->{UserProfile::user_image} = 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=200';
            $profile->save();
        } else {
            $user = User::where(User::email, 'arunvj@accubits.com')->first();
            if (!empty($user)) {

                $request = collect([
                    'name'     => 'Arun',
                    'email'    => 'arunvj@accubits.com',
                    'password' => bcrypt('qwerty@123')
                ]);

                if (!OrgEmployee::where(OrgEmployee::user_id, $user->id)->where(OrgEmployee::org_id, $organization->id)->exists()) {
                    $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);
                }
            }
        }

        if (!User::where('email', 'thuhin@accubits.com')->exists()) {
            $request = collect([
                'name'     => 'Thuhin',
                'email'    => 'thuhin@accubits.com',
                'password' => bcrypt('qwerty@123')
            ]);


            $user = $this->createUser($request);
            $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);

            $profile = new UserProfile();
            $profile->{UserProfile::first_name} = $request->get('name');
            $profile->{UserProfile::last_name}  = $request->get('name');
            $profile->{UserProfile::user_id}    = $user->id;
            $profile->{UserProfile::user_image} = 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=200';
            $profile->save();
        } else {
            $user = User::where(User::email, 'thuhin@accubits.com')->first();
            if (!empty($user)) {

                $request = collect([
                    'name'     => 'Thuhin',
                    'email'    => 'thuhin@accubits.com',
                    'password' => bcrypt('qwerty@123')
                ]);

                if (!OrgEmployee::where(OrgEmployee::user_id, $user->id)->where(OrgEmployee::org_id, $organization->id)->exists()) {
                    $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);
                }
            }
        }

        if (!User::where('email', 'reshman@accubits.com')->exists()) {
            $request = collect([
                'name'     => 'Reshman',
                'email'    => 'reshman@accubits.com',
                'password' => bcrypt('qwerty@123')
            ]);


            $user = $this->createUser($request);
            $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);

            $profile = new UserProfile();
            $profile->{UserProfile::first_name} = $request->get('name');
            $profile->{UserProfile::last_name}  = $request->get('name');
            $profile->{UserProfile::user_id}    = $user->id;
            $profile->{UserProfile::user_image} = 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=200';
            $profile->save();
        } else {
            $user = User::where(User::email, 'reshman@accubits.com')->first();
            if (!empty($user)) {

                $request = collect([
                    'name'     => 'Reshman',
                    'email'    => 'reshman@accubits.com',
                    'password' => bcrypt('qwerty@123')
                ]);

                if (!OrgEmployee::where(OrgEmployee::user_id, $user->id)->where(OrgEmployee::org_id, $organization->id)->exists()) {
                    $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);
                }
            }
        }

        if (!User::where('email', 'aravind@accubits.com')->exists()) {
            $request = collect([
                'name'     => 'Aravind',
                'email'    => 'aravind@accubits.com',
                'password' => bcrypt('qwerty@123')
            ]);


            $user = $this->createUser($request);
            $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);

            $profile = new UserProfile();
            $profile->{UserProfile::first_name} = $request->get('name');
            $profile->{UserProfile::last_name}  = $request->get('name');
            $profile->{UserProfile::user_id}    = $user->id;
            $profile->{UserProfile::user_image} = 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=200';
            $profile->save();
        } else {
            $user = User::where(User::email, 'aravind@accubits.com')->first();
            if (!empty($user)) {

                $request = collect([
                    'name'     => 'Aravind',
                    'email'    => 'aravind@accubits.com',
                    'password' => bcrypt('qwerty@123')
                ]);

                if (!OrgEmployee::where(OrgEmployee::user_id, $user->id)->where(OrgEmployee::org_id, $organization->id)->exists()) {
                    $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);
                }
            }
        }

        if (!User::where('email', 'ramya@accubits.com')->exists()) {
            $request = collect([
                'name'     => 'Ramya',
                'email'    => 'ramya@accubits.com',
                'password' => bcrypt('qwerty@123')
            ]);


            $user = $this->createUser($request);
            $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);

            $profile = new UserProfile();
            $profile->{UserProfile::first_name} = $request->get('name');
            $profile->{UserProfile::last_name}  = $request->get('name');
            $profile->{UserProfile::user_id}    = $user->id;
            $profile->{UserProfile::user_image} = 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=200';
            $profile->save();
        } else {
            $user = User::where(User::email, 'ramya@accubits.com')->first();
            if (!empty($user)) {

                $request = collect([
                    'name'     => 'Ramya',
                    'email'    => 'ramya@accubits.com',
                    'password' => bcrypt('qwerty@123')
                ]);

                if (!OrgEmployee::where(OrgEmployee::user_id, $user->id)->where(OrgEmployee::org_id, $organization->id)->exists()) {
                    $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);
                }
            }
        }

        if (!User::where('email', 'jyothish@accubits.com')->exists()) {
            $request = collect([
                'name'     => 'Jyothish',
                'email'    => 'jyothish@accubits.com',
                'password' => bcrypt('qwerty@123')
            ]);


            $user = $this->createUser($request);
            $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);

            $profile = new UserProfile();
            $profile->{UserProfile::first_name} = $request->get('name');
            $profile->{UserProfile::last_name}  = $request->get('name');
            $profile->{UserProfile::user_id}    = $user->id;
            $profile->{UserProfile::user_image} = 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=200';
            $profile->save();
        } else {
            $user = User::where(User::email, 'jyothish@accubits.com')->first();
            if (!empty($user)) {

                $request = collect([
                    'name'     => 'Jyothish',
                    'email'    => 'jyothish@accubits.com',
                    'password' => bcrypt('qwerty@123')
                ]);

                if (!OrgEmployee::where(OrgEmployee::user_id, $user->id)->where(OrgEmployee::org_id, $organization->id)->exists()) {
                    $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);
                }
            }
        }

        if (!User::where('email', 'bhanu@accubits.com')->exists()) {
            $request = collect([
                'name'     => 'Bhanu',
                'email'    => 'bhanu@accubits.com',
                'password' => bcrypt('qwerty@123')
            ]);


            $user = $this->createUser($request);
            $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);

            $profile = new UserProfile();
            $profile->{UserProfile::first_name} = $request->get('name');
            $profile->{UserProfile::last_name}  = $request->get('name');
            $profile->{UserProfile::user_id}    = $user->id;
            $profile->{UserProfile::user_image} = 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=200';
            $profile->save();
        } else {
            $user = User::where(User::email, 'bhanu@accubits.com')->first();
            if (!empty($user)) {

                $request = collect([
                    'name'     => 'Bhanu',
                    'email'    => 'bhanu@accubits.com',
                    'password' => bcrypt('qwerty@123')
                ]);

                if (!OrgEmployee::where(OrgEmployee::user_id, $user->id)->where(OrgEmployee::org_id, $organization->id)->exists()) {
                    $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);
                }
            }
        }

        if (!User::where('email', 'robinson@accubits.com')->exists()) {
            $request = collect([
                'name'     => 'Robinson',
                'email'    => 'robinson@accubits.com',
                'password' => bcrypt('qwerty@123')
            ]);


            $user = $this->createUser($request);
            $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);

            $profile = new UserProfile();
            $profile->{UserProfile::first_name} = $request->get('name');
            $profile->{UserProfile::last_name}  = $request->get('name');
            $profile->{UserProfile::user_id}    = $user->id;
            $profile->{UserProfile::user_image} = 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=200';
            $profile->save();
        } else {
            $user = User::where(User::email, 'robinson@accubits.com')->first();
            if (!empty($user)) {

                $request = collect([
                    'name'     => 'Robinson',
                    'email'    => 'robinson@accubits.com',
                    'password' => bcrypt('qwerty@123')
                ]);

                if (!OrgEmployee::where(OrgEmployee::user_id, $user->id)->where(OrgEmployee::org_id, $organization->id)->exists()) {
                    $this->createEmployee($request, $user, $organization, $employeeStatus, $licenseId);
                }
            }
        }

    }

    public function createUser($request)
    {
        $user = new User;
        $user->{User::slug}     = User::generateVerificationCode();
        $user->{User::name}     = $request->get('name');
        $user->{User::email}    = $request->get('email');
        $user->{User::password} = ($request->get('password'));
        $user->save();
        $user->assignRole(Roles::ORG_EMPLOYEE);
        return $user;
    }

    public function createEmployee($request, $user, $organization, $employeeStatus, $licenseId)
    {

        $employee = new OrgEmployee;
        $employee->{OrgEmployee::name} = $request->get('name');
        $employee->{OrgEmployee::slug} = User::generateVerificationCode();
        $employee->{OrgEmployee::user_id} = $user->id;
        $employee->{OrgEmployee::org_id}  = $organization->id;
        $employee->{OrgEmployee::employee_status_id} = $employeeStatus->id;
        $employee->{OrgEmployee::org_license_id}     = $licenseId;
        $employee->{OrgEmployee::joining_date}       = now();
        $employee->save();
    }

    public function getOrganizationMapping($organization) : ?OrgLicenseMapping
    {
        return OrgLicenseMapping::where(OrgLicenseMapping::org_id, $organization->id)
            ->where(OrgLicenseMapping::end_date, '>', now())
            ->first();
    }
}
