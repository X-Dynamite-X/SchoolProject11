<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(
            [
                RoleSeeder::class,

            ]
        );
        $user = User::factory()->create([
            'name' => 'dynamite',
            'email' => 'dynamite@gmail.com',
            "password" => "123"
        ]);
        $user->assignRole("admin");
        $user->save();
        $this->call(
            [
                 UserSeeder::class,
                PermissionSeeder::class,
                SubjectSeeder::class,
                SubjectUserSeeder::class
            ]
        );

    }
}
