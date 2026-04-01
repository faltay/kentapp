<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Diller
        $this->call(LanguageSeeder::class);

        // 1. Roller (Spatie: super_admin, verified_contractor, land_owner)
        $this->call(RoleSeeder::class);

        // 2. Abonelik Planları
        $this->call(SubscriptionPlanSeeder::class);

        // 3. Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@kentapp.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'type' => User::TYPE_ADMIN,
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('super_admin');

        // 4. Demo Kullanıcılar
        $this->call(RestaurantSeeder::class);

        // Demo Emlak Danışmanı
        User::firstOrCreate(
            ['email' => 'agent@kentapp.test'],
            [
                'name'               => 'Demo Agent',
                'password'           => Hash::make('password'),
                'is_active'          => true,
                'type'               => User::TYPE_AGENT,
                'email_verified_at'  => now(),
            ]
        );

        // 5. Blog Kategorileri + Yazıları
        $this->call(BlogCategorySeeder::class);
        $this->call(PostSeeder::class);

        // 6. Statik Sayfalar
        $this->call(PageSeeder::class);

        // 7. Ödeme Test Verileri
        $this->call(PaymentSeeder::class);

        // 8. Sistem Ayarları
        $this->call(SettingSeeder::class);
    }
}
