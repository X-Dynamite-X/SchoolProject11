<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(
            [
                PermissionSeeder::class,
                RoleSeeder::class,
            ]
        );

        $user = User::factory()->create([
            'name' => 'dynamite',
            'email' => 'dynamite@gmail.com',
            "password" => Hash::make("123")
        ]);
        $user->assignRole("admin");
        $user->save();
                $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
             "password" => Hash::make("123")
        ]);
        $user->assignRole("admin");
        $user->save();
        User::factory(100)->create();

        $this->call(
            [
                UserSeeder::class,
                PermissionSeeder::class,
                SubjectSeeder::class,
                ConversationSeeder::class,
                MessageSeeder::class,
                // SubjectUserSeeder::class
            ]
        );
    }
}
