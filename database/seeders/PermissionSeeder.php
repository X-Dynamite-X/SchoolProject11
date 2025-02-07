<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $permissions = [
            "allPermission",
            "Read",
            "Write",
            "Update",
            "Delete",
            "Read Users",
            "Delete Users",
            "Update Users",
            "Create Users",
            "Read Roles",
            "Delete Roles",
            "Update Roles",
            "Create Roles",
            "Read Permissions",
            "Delete Permissions",
            "Update Permissions",
            "Create Permissions",
            "Read Subjects",
            "Delete Subjects",
            "Update Subjects",
            "Create Subjects",
            "add user in subject",
            "remove user in subject",
            "edit user in subject",

        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
             ]);
            Permission::firstOrCreate([
                'name' => $permission,
                "guard_name" => "sanctum"
            ]);
        }
    }
}
