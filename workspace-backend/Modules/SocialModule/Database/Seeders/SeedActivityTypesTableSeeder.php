<?php

namespace Modules\SocialModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\SocialModule\Entities\SocialActivityStreamType;
use Illuminate\Support\Facades\DB;

class SeedActivityTypesTableSeeder extends Seeder
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
                SocialActivityStreamType::name => 'message',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialActivityStreamType::name => 'task',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialActivityStreamType::name => 'event',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialActivityStreamType::name => 'announcement',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialActivityStreamType::name => 'poll',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialActivityStreamType::name => 'appreciation',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialActivityStreamType::name => 'workflow',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialActivityStreamType::name => 'form',
                'created_at' => date("Y-m-d H:i:s")
            ]
        ];

        foreach ($data as $value) {

            $valueArray = $value;
            unset($value['created_at']);
            $mainKeysArray = $value;

            SocialActivityStreamType::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
