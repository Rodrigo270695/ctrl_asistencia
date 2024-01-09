<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            "name"=> "admin",
            "email"=> "rodrigo_06_27@hotmail.com",
            "pdv_id"=> 1,
            "password"=> Hash::make("admin")
        ]);
        $admin->assignRole("admin");

/*         $editor = User::create([
            "name"=> "editor",
            "email"=> "editor@hotmail.com",
            "pdv_id"=> 127,
            "password"=> Hash::make("editor")
        ]);
        $editor->assignRole("editor");

        $supervisor = User::create([
            "name"=> "supervisor",
            "email"=> "supervisor@hotmail.com",
            "pdv_id"=> 31,
            "password"=> Hash::make("supervisor")
        ]);
        $supervisor->assignRole("supervisor");

        $pdv = User::create([
            "name"=> "pdv",
            "email"=> "pdv@hotmail.com",
            "pdv_id"=> 31,
            "password"=> Hash::make("pdv")
        ]);
        $pdv->assignRole("pdv"); */
    }
}
