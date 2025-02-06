<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            "admin",
            "user"
        ];

        foreach ($roles as $roleName) {
            $newRole = Role::firstOrCreate([
                'name' => $roleName,
            ]);

            if ($newRole->name === "admin") {
                $newRole->givePermissionTo("allPermission");
            } elseif ($newRole->name === "user") {
                $newRole->givePermissionTo(["Read", "Write", "Update", "Delete"]);
            }
        }
    }
}
