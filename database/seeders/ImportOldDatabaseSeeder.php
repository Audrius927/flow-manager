<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportOldDatabaseSeeder extends Seeder
{
    /**
     * Senosios duomenų bazės connection pavadinimas
     * Pridėkite į config/database.php:
     * 
     * 'old_database' => [
     *     'driver' => 'mysql',
     *     'host' => env('OLD_DB_HOST', '127.0.0.1'),
     *     'port' => env('OLD_DB_PORT', '3306'),
     *     'database' => env('OLD_DB_DATABASE', 'geauta_lrv'),
     *     'username' => env('OLD_DB_USERNAME', 'root'),
     *     'password' => env('OLD_DB_PASSWORD', ''),
     *     ...
     * ]
     */
    protected $oldConnection = 'old_database';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Patikrinti ar egzistuoja senosios DB connection
        if (!config("database.connections.{$this->oldConnection}")) {
            $this->command->error("Nerastas '{$this->oldConnection}' connection. Patikrinkite config/database.php");
            return;
        }

        $this->command->info('Pradedamas duomenų importas iš senosios duomenų bazės...');
        
        try {
            DB::connection($this->oldConnection)->getPdo();
        } catch (\Exception $e) {
            $this->command->error('Nepavyko prisijungti prie senosios duomenų bazės: ' . $e->getMessage());
            return;
        }

        // Išvalyti esamus duomenis (neprivaloma)
        $this->command->warn('ĮSPĖJIMAS: Visi esami duomenys bus ištrinti!');
        if (!$this->command->confirm('Tęsti?', true)) {
            return;
        }

        $this->truncateTables();
        
        // Importuoti duomenis
        $this->importMarksAndModels();
        $this->importPartCategories();
        $this->importEngines();
        $this->importFuelTypes();
        $this->importBodyTypes();
        
        $this->command->info('✅ Duomenų importas baigtas sėkmingai!');
    }

    /**
     * Išvalyti lenteles
     */
    protected function truncateTables(): void
    {
        $this->command->info('Išvalomos lentelės...');
        
        // Svarbu tvarka dėl foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('body_types')->truncate();
        DB::table('fuel_types')->truncate();
        DB::table('engines')->truncate();
        DB::table('part_categories')->truncate();
        DB::table('car_models')->truncate();
        DB::table('car_marks')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Importuoti marks ir models
     */
    protected function importMarksAndModels(): void
    {
        $this->command->info('Importuojamos marks ir models...');
        
        $oldCars = DB::connection($this->oldConnection)
            ->table('car_categories')
            ->orderBy('parent_id', 'asc') // Pirmiausia markės (be parent_id), paskui modeliai (su parent_id)
            ->orderBy('id', 'asc')
            ->get();

        $mapping = []; // Senas ID => Naujas ID (marks arba models)
        $marksMapping = []; // Senas car_categories ID => Naujas mark ID

        foreach ($oldCars as $oldCar) {
            if ($oldCar->parent_id === null) {
                // Be parent_id = Markė
                $markId = DB::table('car_marks')->insertGetId([
                    'title' => $oldCar->title,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $marksMapping[$oldCar->id] = $markId;
                $mapping[$oldCar->id] = $markId;
            } else {
                // Su parent_id = Modelis
                // Reikia gauti car_mark_id iš parent_id
                $markId = $marksMapping[$oldCar->parent_id] ?? null;
                
                if ($markId) {
                    $modelId = DB::table('car_models')->insertGetId([
                        'title' => $oldCar->title,
                        'car_mark_id' => $markId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $mapping[$oldCar->id] = $modelId;
                } else {
                    $this->command->warn("⚠️ Nerastas car_mark_id parent_id={$oldCar->parent_id} modeliui: {$oldCar->title}");
                }
            }
        }

        $marksCount = DB::table('car_marks')->count();
        $modelsCount = DB::table('car_models')->count();
        $this->command->info("✅ Importuota car_marks: {$marksCount}, car_models: {$modelsCount}");
    }

    /**
     * Importuoti part_categories su vertimais (lt kalba)
     */
    protected function importPartCategories(): void
    {
        $this->command->info('Importuojamos part_categories...');
        
        $oldCategories = DB::connection($this->oldConnection)
            ->table('part_categories')
            ->orderBy('parent_1_id', 'asc')
            ->orderBy('parent_2_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $mapping = []; // Senas ID => Naujas ID

        foreach ($oldCategories as $oldCategory) {
            // Gauti lietuvišką vertimą
            $translation = DB::connection($this->oldConnection)
                ->table('part_category_translations')
                ->where('part_category_id', $oldCategory->id)
                ->where('locale', 'lt')
                ->first();

            // Jei nėra lietuviško, imti pirmą pateiktą
            if (!$translation) {
                $translation = DB::connection($this->oldConnection)
                    ->table('part_category_translations')
                    ->where('part_category_id', $oldCategory->id)
                    ->first();
            }

            $title = $translation ? $translation->title : 'Be pavadinimo';

            // Konvertuoti seną struktūrą (parent_1_id, parent_2_id) į naują (parent_id)
            // Jei yra parent_2_id - tai tiesioginis tėvas (3 lygio kategorija)
            // Jei yra tik parent_1_id - tai tiesioginis tėvas (2 lygio kategorija)
            // Jei nėra nei vieno - tai root kategorija (1 lygis)
            $parentId = null;
            if ($oldCategory->parent_2_id && isset($mapping[$oldCategory->parent_2_id])) {
                $parentId = $mapping[$oldCategory->parent_2_id];
            } elseif ($oldCategory->parent_1_id && isset($mapping[$oldCategory->parent_1_id])) {
                $parentId = $mapping[$oldCategory->parent_1_id];
            }

            $newCategory = DB::table('part_categories')->insertGetId([
                'title' => $title,
                'parent_id' => $parentId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $mapping[$oldCategory->id] = $newCategory;
        }

        $this->command->info("✅ Importuota part_categories: " . count($oldCategories));
    }

    /**
     * Importuoti engines
     */
    protected function importEngines(): void
    {
        $this->command->info('Importuojamos engines...');
        
        $oldEngines = DB::connection($this->oldConnection)
            ->table('engines')
            ->get();

        foreach ($oldEngines as $oldEngine) {
            DB::table('engines')->insert([
                'title' => $oldEngine->title,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info("✅ Importuota engines: " . count($oldEngines));
    }

    /**
     * Importuoti fuel_types su vertimais (lt kalba)
     */
    protected function importFuelTypes(): void
    {
        $this->command->info('Importuojamos fuel_types...');
        
        $oldFuelTypes = DB::connection($this->oldConnection)
            ->table('fuel_types')
            ->get();

        foreach ($oldFuelTypes as $oldFuelType) {
            // Gauti lietuvišką vertimą
            $translation = DB::connection($this->oldConnection)
                ->table('fuel_type_translations')
                ->where('fuel_type_id', $oldFuelType->id)
                ->where('locale', 'lt')
                ->first();

            // Jei nėra lietuviško, imti pirmą pateiktą
            if (!$translation) {
                $translation = DB::connection($this->oldConnection)
                    ->table('fuel_type_translations')
                    ->where('fuel_type_id', $oldFuelType->id)
                    ->first();
            }

            $title = $translation ? $translation->title : 'Be pavadinimo';

            DB::table('fuel_types')->insert([
                'title' => $title,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info("✅ Importuota fuel_types: " . count($oldFuelTypes));
    }

    /**
     * Importuoti body_types su vertimais (lt kalba)
     */
    protected function importBodyTypes(): void
    {
        $this->command->info('Importuojamos body_types...');
        
        $oldBodyTypes = DB::connection($this->oldConnection)
            ->table('body_types')
            ->get();

        foreach ($oldBodyTypes as $oldBodyType) {
            // Gauti lietuvišką vertimą
            $translation = DB::connection($this->oldConnection)
                ->table('body_type_translations')
                ->where('body_type_id', $oldBodyType->id)
                ->where('locale', 'lt')
                ->first();

            // Jei nėra lietuviško, imti pirmą pateiktą
            if (!$translation) {
                $translation = DB::connection($this->oldConnection)
                    ->table('body_type_translations')
                    ->where('body_type_id', $oldBodyType->id)
                    ->first();
            }

            $title = $translation ? $translation->title : 'Be pavadinimo';

            DB::table('body_types')->insert([
                'title' => $title,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info("✅ Importuota body_types: " . count($oldBodyTypes));
    }
}

