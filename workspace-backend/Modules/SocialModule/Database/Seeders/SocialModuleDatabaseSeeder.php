<?php

namespace Modules\SocialModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\SocialModule\Database\Seeders\SeedSocialLookupTableTableSeeder;

class SocialModuleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(SeedActivityTypesTableSeeder::class);
        $this->call(SeedSocialLookupTableTableSeeder::class);
        $this->call(SeedSocialResponseTypesTableSeeder::class);

    }
}
