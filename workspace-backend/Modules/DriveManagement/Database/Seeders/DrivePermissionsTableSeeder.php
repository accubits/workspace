<?php

namespace Modules\DriveManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\Utilities;
use Modules\DriveManagement\Entities\DrivePermissions;

class DrivePermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $data = [
            [
                DrivePermissions::slug => Utilities::getUniqueId(),
                DrivePermissions::name => 'can view',
                DrivePermissions::type => DrivePermissions::can_view,
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                DrivePermissions::slug => Utilities::getUniqueId(),
                DrivePermissions::name => 'can update',
                DrivePermissions::type => DrivePermissions::can_update,
                'created_at'          => now(),
                'updated_at'          => now()
            ]
        ];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            unset($value['updated_at']);
            unset($value[DrivePermissions::slug]);
            unset($value[DrivePermissions::name]);
            $mainKeysArray = $value;

            DrivePermissions::updateOrCreate($mainKeysArray, $valueArray);
        }

    }
}
