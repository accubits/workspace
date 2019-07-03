<?php

namespace Modules\FormManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\FormManagement\Entities\FormComponentType;

class SeedFormComponentTypeTableSeeder extends Seeder
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
                FormComponentType::cmp_type_displayname => 'Single Line Text',
                FormComponentType::cmp_type_name => 'singleLineText',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Paragraph Text',
                FormComponentType::cmp_type_name => 'paragraphText',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Number',
                FormComponentType::cmp_type_name => 'number',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Section Break',
                FormComponentType::cmp_type_name => 'section',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Page Break',
                FormComponentType::cmp_type_name => 'page',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Checkboxes',
                FormComponentType::cmp_type_name => 'checkboxes',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Multiple Choice',
                FormComponentType::cmp_type_name => 'multipleChoice',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Drop down',
                FormComponentType::cmp_type_name => 'dropdown',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Name',
                FormComponentType::cmp_type_name => 'name',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Email',
                FormComponentType::cmp_type_name => 'email',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Phone',
                FormComponentType::cmp_type_name => 'phone',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Date',
                FormComponentType::cmp_type_name => 'date',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Time',
                FormComponentType::cmp_type_name => 'time',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Website',
                FormComponentType::cmp_type_name => 'website',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Address',
                FormComponentType::cmp_type_name => 'address',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'File Upload',
                FormComponentType::cmp_type_name => 'fileUpload',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Rating',
                FormComponentType::cmp_type_name => 'rating',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Likert',
                FormComponentType::cmp_type_name => 'likert',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                FormComponentType::cmp_type_displayname => 'Price',
                FormComponentType::cmp_type_name => 'price',
                FormComponentType::is_active => TRUE,
                'created_at' => date("Y-m-d H:i:s")
            ]
        ];

        //DB::table(FormComponentType::table)->insert($data);
        foreach ($data as $value) {
            $valueArray = $value;
            unset($value['created_at']);
            $mainKeysArray = $value;
            FormComponentType::updateOrCreate($mainKeysArray, $valueArray);
        }        
        // $this->call("OthersTableSeeder");
    }
}
