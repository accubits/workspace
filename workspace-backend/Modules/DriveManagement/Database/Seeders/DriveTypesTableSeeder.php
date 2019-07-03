<?php

namespace Modules\DriveManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\Utilities;
use Modules\DriveManagement\Entities\DriveType;

class DriveTypesTableSeeder extends Seeder
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
                DriveType::slug => Utilities::getUniqueId(),
                DriveType::name           => DriveType::my_drive,
                DriveType::display_name   => 'My Drive',
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                DriveType::slug => Utilities::getUniqueId(),
                DriveType::name           => DriveType::company_drive,
                DriveType::display_name   => 'Company Drive',
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                DriveType::slug => Utilities::getUniqueId(),
                DriveType::name           => DriveType::shared_me,
                DriveType::display_name   => 'Shared With Me',
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                DriveType::slug => Utilities::getUniqueId(),
                DriveType::name           => DriveType::trash,
                DriveType::display_name   => 'Trash',
                'created_at'          => now(),
                'updated_at'          => now()
            ]
        ];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            unset($value['updated_at']);
            unset($value[DriveType::slug]);
            unset($value[DriveType::display_name]);
            $mainKeysArray = $value;

            DriveType::updateOrCreate($mainKeysArray, $valueArray);
        }

    }
}
