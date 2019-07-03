<?php

namespace Modules\HrmManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\HrmManagement\Entities\HrmKraQuestionType;

class SeedKraQuestionTypesTableSeeder extends Seeder
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
                HrmKraQuestionType::display_name => 'Rating',
                HrmKraQuestionType::type_name => 'rating',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                HrmKraQuestionType::display_name => 'Answer',
                HrmKraQuestionType::type_name => 'answerText',
                'created_at' => date("Y-m-d H:i:s")
            ]];

        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            $mainKeysArray = $value;
            HrmKraQuestionType::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
