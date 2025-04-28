<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'name' => 'edit_documents',
                'description' => 'Can edit documents'
            ],
            [
                'name' => 'delete_documents',
                'description' => 'Can delete documents'
            ],
            [
                'name' => 'assign_access_level_1',
                'description' => 'Can assign access level 1'
            ],
            [
                'name' => 'assign_access_level_2',
                'description' => 'Can assign access level 2'
            ],
            [
                'name' => 'assign_access_level_3',
                'description' => 'Can assign access level 3'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
