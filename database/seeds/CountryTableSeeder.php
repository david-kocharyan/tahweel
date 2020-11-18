<?php

use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new \App\Model\Country();
        $admin->name = "Saudi Arabia";
        $admin->save();
    }
}
