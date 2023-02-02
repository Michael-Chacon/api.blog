<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => 'admin']);
        $create_post = Permission::create(['name' => 'create post']);
        $edit_post = Permission::create(['name' => 'Edit post']);
        $delete_post = Permission::create(['name' => 'delete post']);

        $admin->syncPermissions([$create_post, $edit_post, $delete_post]);
    }
}
