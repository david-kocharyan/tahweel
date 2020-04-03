<?php

use App\Model\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Admin;
        $admin->name = "Admin";
        $admin->email = "admin@gmail.com";
        $admin->password = Hash::make('123456');
        $admin->save();
    }
}
