<?php

namespace Modules\DriveManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\Utilities;
use Modules\DriveManagement\Entities\Drive;
use Modules\DriveManagement\Entities\DriveType;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\UserManagement\Entities\User;

class DriveTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $orgEmployees = OrgEmployee::select(OrgEmployee::user_id, OrgEmployee::org_id)
            ->join(User::table, User::table. '.id', '=', OrgEmployee::table. '.' .OrgEmployee::user_id)->get();

        $drive = $this->addDrive($orgEmployees);

    }

    public function addDrive($orgEmployees)
    {
        collect($orgEmployees)->map(function ($employee) {
            $mydriveId      = DriveType::where(DriveType::name, DriveType::my_drive)->first()->id;
            $companyDriveId = DriveType::where(DriveType::name, DriveType::company_drive)->first()->id;

            if (Drive::where(Drive::org_id, $employee->{OrgEmployee::org_id})
                ->where(Drive::user_id, $employee->{OrgEmployee::user_id})
                ->where(Drive::drive_type_id, $mydriveId)->doesntExist()) {
                $drive = new Drive;
                $drive->{Drive::slug} = Utilities::getUniqueId();
                $drive->{Drive::org_id} = $employee->{OrgEmployee::org_id};
                $drive->{Drive::user_id} = $employee->{OrgEmployee::user_id};
                $drive->{Drive::drive_type_id} = $mydriveId;
                $drive->save();
            }

            if (Drive::where(Drive::org_id, $employee->{OrgEmployee::org_id})
                ->where(Drive::user_id, $employee->{OrgEmployee::user_id})
                ->where(Drive::drive_type_id, $companyDriveId)->doesntExist()) {
                $drive = new Drive;
                $drive->{Drive::slug} = Utilities::getUniqueId();
                $drive->{Drive::org_id} = $employee->{OrgEmployee::org_id};
                $drive->{Drive::user_id} = $employee->{OrgEmployee::user_id};
                $drive->{Drive::drive_type_id} = $companyDriveId;
                $drive->save();
            }
        });
    }
}
