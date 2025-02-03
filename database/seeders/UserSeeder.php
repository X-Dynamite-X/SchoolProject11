<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
            //    User::factory(10)->create();

        // for ($count = 2; $count <= 11; $count++) {
        //     $user = User::find($count);
        //     $user->assignRole("user");
        //     $user->save();
        // }
        for($count =0 ;$count <= 150 ;$count++){
            User::create([
                "name"=>"dynamite_".$count,
                "email"=>"dynamite_".$count."@gmail.com",
                "password"=>"123"
            ])->assignRole("user");
        };
    }
}
