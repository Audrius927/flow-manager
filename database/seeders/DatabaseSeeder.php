<?php

namespace Database\Seeders;

use App\Enums\SystemRole;
use App\Models\User;
use Database\Seeders\DamageCaseSeeder;
use Database\Seeders\PartStorageSeeder;
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

        User::factory()->count(20)->create([
            'system_role' => SystemRole::User,
            'password' => Hash::make('qwer'),
        ]);

        $this->call([
            RolePermissionSeeder::class,
            PartStorageSeeder::class,
            DamageCaseSeeder::class,
        ]);
    }
}
