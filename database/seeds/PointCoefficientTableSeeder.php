<?php

use Illuminate\Database\Seeder;

class PointCoefficientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Model\PointCoeficient::insert([
            ["name" => "Bathrooms", "code" => "BA"],
            ["name" => "Kitchen", "code" => "KI"],
            ["name" => "Service Counters", "code" => "SC"],
        ]);
    }
}
