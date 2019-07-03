<?php

namespace Modules\SocialModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\SocialModule\Entities\SocialResponseType;

class SeedSocialResponseTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        //order important
        $data = [
            [
                SocialResponseType::resp_slug => uniqid(),
                SocialResponseType::response_text => 'like',
                'created_at' => date("Y-m-d H:i:s")
            ]
        ];
        
        foreach ($data as $value) {
            $responseTypeObj = SocialResponseType::where(SocialResponseType::response_text, '=', $value[SocialResponseType::response_text])->first();
            if(empty($responseTypeObj)){
                SocialResponseType::create($value);
            }
        }
    }
}
