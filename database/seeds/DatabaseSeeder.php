<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(GlobalSettingsSeeder::class);
        $this->call(SpecSeeder::class);
        $this->call(UserSeed::class);
    }
}
