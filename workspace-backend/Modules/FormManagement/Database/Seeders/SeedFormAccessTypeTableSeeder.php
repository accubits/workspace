<?php

namespace Modules\FormManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\FormManagement\Entities\FormAccessType;

class SeedFormAccessTypeTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Model::unguard();

        $data = [
            [
                FormAccessType::name => 'internal',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormAccessType::name => 'public',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormAccessType::name => 'postTrainingFeedbackForm', //trainingFeedbackForm
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormAccessType::name => 'postCourseFeedbackForm',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormAccessType::name => 'crmLeadForm',
                'created_at' => date("Y-m-d H:i:s")
            ]
        ];

        //DB::table(FormAccessType::table)->insert($data);
        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            $mainKeysArray = $value;
            FormAccessType::updateOrCreate($mainKeysArray, $valueArray);
        }
        // $this->call("OthersTableSeeder");
    }

}
