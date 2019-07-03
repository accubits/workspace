<?php

namespace Modules\FormManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\FormManagement\Entities\FormStatus;

class SeedFormStatusTableSeeder extends Seeder
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
                FormStatus::status_name => 'draft',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormStatus::status_name => 'inactive',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormStatus::status_name => 'active',
                'created_at' => date("Y-m-d H:i:s")
            ]
        ];

        //DB::table(FormStatus::table)->insert($data);
        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            $mainKeysArray = $value;
            FormStatus::updateOrCreate($mainKeysArray, $valueArray);
        }
        // $this->call("OthersTableSeeder");
    }
}
