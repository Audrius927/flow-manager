<?php

namespace Database\Seeders;

use App\Enums\SystemRole;
use App\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\DamageCaseSeeder;
use Database\Seeders\InsuranceCompanySeeder;
use Database\Seeders\PartStorageSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\RepairCompanySeeder;
use Database\Seeders\RolePermissionSeeder;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('qwer'),
            'system_role' => SystemRole::Admin,
        ]);

        // Importuoti automobilių duomenis per konsolės komandą
        Artisan::call('auto-data:import');

        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => "vardas {$i} pavarde {$i}",
                'email' => "vardas{$i}pavarde{$i}@example.com",
                'password' => Hash::make('qwer'),
                'system_role' => SystemRole::User,
                'email_verified_at' => now(),
            ]);
        }

        $this->call([
            RolePermissionSeeder::class,
            InsuranceCompanySeeder::class,
            RepairCompanySeeder::class,
            ProductSeeder::class,
            CitySeeder::class,
            PartStorageSeeder::class,
            DamageCaseSeeder::class,
        ]);
    }
}
