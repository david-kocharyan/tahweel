<?php

use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Model\Language::insert([
            ["lng" => "en"],
            ["lng" => "ar"],
        ]);
    }
}
