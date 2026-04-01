<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Cache'i sıfırla
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Spatie rolleri — yalnızca hesap türünü belirler
        // Altındaki roller (restaurant_manager, branch_manager, custom) staff_roles tablosunda tutulur
        Role::firstOrCreate(['name' => 'super_admin',         'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'verified_contractor', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'land_owner',          'guard_name' => 'web']);
    }
}
