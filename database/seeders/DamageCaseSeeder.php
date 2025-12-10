<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\DamageCase;
use App\Models\InsuranceCompany;
use App\Models\Product;
use App\Models\RepairCompany;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DamageCaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('lt_LT');

        $users = User::where('system_role', 'user')->pluck('id')->all();
        $carMarks = \App\Models\CarMark::with('models')->get();
        $carModels = \App\Models\CarModel::with('mark')->get();
        $repairCompanyIds = RepairCompany::pluck('id')->all();
        $insuranceCompanyIds = InsuranceCompany::pluck('id')->all();
        $cityIds = City::pluck('id')->all();
        $productIds = Product::whereNull('parent_id')->pluck('id')->all();
        $transportasProduct = Product::where('title', 'TRANSPORTAS')->whereNull('parent_id')->first();
        $transportSubProductIds = $transportasProduct 
            ? Product::where('parent_id', $transportasProduct->id)->pluck('id')->all() 
            : [];
        $damageCasesToCreate = 1;

        for ($i = 1; $i <= $damageCasesToCreate; $i++) {
            $orderDate = Carbon::now()->subDays(rand(0, 180))->startOfDay();
            $receivedAt = (clone $orderDate)->addDays(rand(0, 5))->setTime(rand(8, 18), rand(0, 59));

            $carMark = $carMarks->isNotEmpty() ? $carMarks->random() : null;
            $carModel = null;

            if ($carMark && $carMark->models->isNotEmpty()) {
                $carModel = $carMark->models->random();
            } elseif ($carModels->isNotEmpty()) {
                $carModel = $carModels->random();
                $carMark = $carModel->mark;
            }

            $removedFromStorage = $faker->boolean(35) ? (clone $receivedAt)->addDays(rand(1, 15)) : null;
            $returnedToStorage = $removedFromStorage ? (clone $removedFromStorage)->addDays(rand(1, 10)) : null;
            $returnedToClient = $returnedToStorage ? (clone $returnedToStorage)->addDays(rand(1, 7)) : null;
            $finishedAt = $faker->boolean(40) ? (clone $orderDate)->addDays(rand(20, 60)) : null;

            $productId = !empty($productIds) ? $faker->optional(0.8)->randomElement($productIds) : null;
            $productSubId = null;
            if ($productId && $transportasProduct && $productId === $transportasProduct->id && !empty($transportSubProductIds)) {
                $productSubId = $faker->optional(0.7)->randomElement($transportSubProductIds);
            }

            $damageCase = DamageCase::create([
                'insurance_company_id' => !empty($insuranceCompanyIds) ? $faker->optional(0.8)->randomElement($insuranceCompanyIds) : null,
                'product_id' => $productId,
                'product_sub_id' => $productSubId,
                'damage_number' => strtoupper('DC-' . $faker->unique()->numerify('####-##')),
                'car_mark_id' => $carMark?->id,
                'car_model_id' => $carModel?->id,
                'license_plate' => strtoupper($faker->bothify('???-####')),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'phone' => $faker->phoneNumber,
                'order_date' => $orderDate,
                'received_at' => $receivedAt,
                'city_id' => !empty($cityIds) ? $faker->optional(0.8)->randomElement($cityIds) : null,
                'received_location' => $faker->address,
                'storage_location' => $faker->address,
                'removed_from_storage_at' => $removedFromStorage,
                'returned_to_storage_at' => $returnedToStorage,
                'returned_to_client_at' => $returnedToClient,
                'repair_company_id' => !empty($repairCompanyIds) ? $faker->optional(0.7)->randomElement($repairCompanyIds) : null,
                'planned_repair_start' => (clone $orderDate)->addDays(rand(3, 15)),
                'planned_repair_end' => (clone $orderDate)->addDays(rand(20, 40)),
                'finished_at' => $finishedAt,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!empty($users)) {
                $assignedUsers = $faker->randomElements($users, rand(1, min(3, count($users))));
                $damageCase->users()->attach($assignedUsers);
            }
        }
    }
}

