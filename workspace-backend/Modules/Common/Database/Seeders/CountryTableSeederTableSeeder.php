<?php

namespace Modules\Common\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Common\Entities\Country;
use Modules\Common\Utilities\Utilities;

class CountryTableSeederTableSeeder extends Seeder
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
                Country::slug => Utilities::getUniqueId(),
                Country::name => 'Singapore',
                Country::is_active => 1,
                'created_at'          => now(),
                'updated_at'          => now()
            ],
            [
                Country::slug => Utilities::getUniqueId(),
                Country::name => 'India',
                Country::is_active => 1,
                'created_at'          => now(),
                'updated_at'          => now()
            ]
        ];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            unset($value['updated_at']);
            unset($value[Country::slug]);
            $mainKeysArray = $value;

            Country::updateOrCreate($mainKeysArray, $valueArray);
        }

    }
}
