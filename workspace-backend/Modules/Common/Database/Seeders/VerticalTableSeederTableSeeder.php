<?php

namespace Modules\Common\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Common\Entities\Vertical;
use Modules\Common\Utilities\Utilities;

class VerticalTableSeederTableSeeder extends Seeder
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
                Vertical::name => 'Schools',
                Vertical::slug => Utilities::getUniqueId(),
                Vertical::description => 'Schools',
                Vertical::is_active => 1,
                'created_at'          => now(),
                'updated_at'          => now()
            ]
        ];



        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            unset($value['updated_at']);
            unset($value[Vertical::slug]);
            unset($value[Vertical::description]);
            $mainKeysArray = $value;

            Vertical::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
