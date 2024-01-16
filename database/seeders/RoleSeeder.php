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
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'Admin']);
        $seller = Role::create(['name' => 'Seller']);
        $customer = Role::create(['name' => 'Customer']);

        Permission::create(['name' => 'edit Sellers'])->assignRole($admin);
        Permission::create(['name' => 'edit Customers'])->syncRoles([$admin,$customer]);
        Permission::create(['name' => 'edit Products'])->syncRoles([$admin,$seller]);
        Permission::create(['name' => 'edit Orders'])->syncRoles([$admin,$customer]);

    }
}
