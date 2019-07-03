<?php

namespace Modules\SocialModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\SocialModule\Entities\SocialLookup;
use Modules\UserManagement\Entities\Roles;

class SeedSocialLookupTableTableSeeder extends Seeder
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

            //event -> reminder
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'reminder',
                SocialLookup::value => 'Minutes',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'reminder',
                SocialLookup::value => 'Hours',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'reminder',
                SocialLookup::value => 'Days',
                'created_at' => date("Y-m-d H:i:s")
            ],


            //event -> repeat
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'repeat',
                SocialLookup::value => 'Never',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'repeat',
                SocialLookup::value => 'Daily',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'repeat',
                SocialLookup::value => 'Weekly',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'repeat',
                SocialLookup::value => 'Monthly',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'repeat',
                SocialLookup::value => 'Yearly',
                'created_at' => date("Y-m-d H:i:s")
            ],


            //event -> importance
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'importance',
                SocialLookup::value => 'Low',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'importance',
                SocialLookup::value => 'Normal',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'importance',
                SocialLookup::value => 'High',
                'created_at' => date("Y-m-d H:i:s")
            ],


            //event -> availability
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'availability',
                SocialLookup::value => 'Occupied',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'availability',
                SocialLookup::value => 'Undecided',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'availability',
                SocialLookup::value => 'Free',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialLookup::title => 'event',
                SocialLookup::attribute => 'availability',
                SocialLookup::value => 'Away (Add to Absence Chart)',
                'created_at' => date("Y-m-d H:i:s")
            ],
            
            //poll  -> status
            [
                SocialLookup::title => 'poll',
                SocialLookup::attribute => 'status',
                SocialLookup::value => 'Open',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialLookup::title => 'poll',
                SocialLookup::attribute => 'status',
                SocialLookup::value => 'Closed',
                'created_at' => date("Y-m-d H:i:s")
            ],
            
            //appreciation  -> status
            [
                SocialLookup::title => 'appreciation',
                SocialLookup::attribute => 'status',
                SocialLookup::value => 'Show',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                SocialLookup::title => 'appreciation',
                SocialLookup::attribute => 'status',
                SocialLookup::value => 'Hide',
                'created_at' => date("Y-m-d H:i:s")
            ]
        ];

        foreach ($data as $value) {

            $valueArray = $value;
            unset($value['created_at']);
            $mainKeysArray = $value;

            SocialLookup::updateOrCreate($mainKeysArray, $valueArray);
        }
        
        
        //org_admin role
        $roleData = [
            [
                Roles::name => Roles::ORG_ADMIN,
                Roles::guard_name => 'web'
            ]
        ];
        
        foreach ($roleData as $value) {
            $valueArray = $value;
            $mainKeysArray = $value;
            Roles::updateOrCreate($mainKeysArray, $valueArray);
        }
    }
}
