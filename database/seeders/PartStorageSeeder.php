<?php

namespace Database\Seeders;

use App\Models\BodyType;
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\FuelType;
use App\Models\PartCategory;
use App\Models\PartStorage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PartStorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = fake('lt_LT');

        $partCategoryIds = PartCategory::pluck('id')->all();
        $carModelIds = CarModel::pluck('id')->all();
        $engineIds = Engine::pluck('id')->all();
        $fuelTypeIds = FuelType::pluck('id')->all();
        $bodyTypeIds = BodyType::pluck('id')->all();

        if (empty($partCategoryIds)) {
            $this->command?->warn('⚠️ PartStorageSeeder: nėra kategorijų, todėl įrašai nebus sukurti.');
            return;
        }

        $recordsToCreate = 500;
        $chunkSize = 100;
        $now = Carbon::now();

        $records = [];

        // for ($i = 1; $i <= $recordsToCreate; $i++) {
            $records[] = [
                'part_number' => strtoupper($faker->unique()->bothify('PS-####-??')),
                'part_category_id' => $faker->randomElement($partCategoryIds),
                'car_model_id' => !empty($carModelIds) ? $faker->randomElement($carModelIds) : null,
                'engine_id' => !empty($engineIds) ? $faker->optional(0.6)->randomElement($engineIds) : null,
                'fuel_type_id' => !empty($fuelTypeIds) ? $faker->optional(0.6)->randomElement($fuelTypeIds) : null,
                'body_type_id' => !empty($bodyTypeIds) ? $faker->optional(0.5)->randomElement($bodyTypeIds) : null,
                'year' => $faker->numberBetween(2000, now()->year + 1),
                'quantity' => $faker->numberBetween(1, 25),
                'vin_code' => $faker->optional()->regexify('[A-HJ-NPR-Z0-9]{17}'),
                'notes' => $faker->optional()->sentence(),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        // }

        foreach (array_chunk($records, $chunkSize) as $chunk) {
            PartStorage::insert($chunk);
        }

        $this->command?->info("✅ Masiniu būdu sukurta {$recordsToCreate} part_storages įrašų.");
    }
}

