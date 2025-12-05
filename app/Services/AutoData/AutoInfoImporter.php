<?php

namespace App\Services\AutoData;

use App\Models\BodyType;
use App\Models\CarMark;
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\FuelType;
use App\Models\PartCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class AutoInfoImporter
{
    /**
     * Importuoja JSON failus į duomenų bazę.
     *
     * @return array<string, int> Importuotų įrašų skaičiai.
     */
    public function import(string $directory = 'auto-data', string $diskName = 'local', bool $truncate = true): array
    {
        $disk = Storage::disk($diskName);
        $directory = trim($directory, '/');

        if ($directory === '') {
            throw new RuntimeException('Importo katalogas negali būti tuščias.');
        }

        if (!$disk->exists($directory)) {
            throw new RuntimeException("Nerastas katalogas: {$disk->path($directory)}");
        }

        $files = [
            'car_marks' => "{$directory}/markes_modeliai.json",
            'part_categories' => "{$directory}/part_categories.json",
            'fuel_types' => "{$directory}/fuel_types.json",
            'body_types' => "{$directory}/body_types.json",
            'engines' => "{$directory}/engines.json",
        ];

        foreach ($files as $label => $path) {
            if (!$disk->exists($path)) {
                throw new RuntimeException("Trūksta failo: {$disk->path($path)} ({$label})");
            }
        }

        if ($truncate) {
            $this->truncateTables();
        }

        return DB::transaction(function () use ($disk, $files) {
            $counts = [];

            $counts['car_marks'] = $this->importMarksAndModels(
                $this->readJson($disk->path($files['car_marks']))
            );
            $counts['part_categories'] = $this->importPartCategories(
                $this->readJson($disk->path($files['part_categories']))
            );
            $counts['fuel_types'] = $this->importSimpleTable(
                FuelType::class,
                $this->readJson($disk->path($files['fuel_types']))
            );
            $counts['body_types'] = $this->importSimpleTable(
                BodyType::class,
                $this->readJson($disk->path($files['body_types']))
            );
            $counts['engines'] = $this->importSimpleTable(
                Engine::class,
                $this->readJson($disk->path($files['engines']))
            );

            return $counts;
        });
    }

    private function importMarksAndModels(array $payload): int
    {
        $modelsCount = 0;

        // Naujo JSON struktūra: { "meta": {...}, "makes": [...] }
        $makes = $payload['makes'] ?? $payload; // Jei nėra 'makes', naudoti visą payload (atgalinis suderinamumas)

        foreach ($makes as $item) {
            $mark = CarMark::query()->create([
                'title' => $item['title'],
            ]);

            foreach ($item['models'] ?? [] as $model) {
                CarModel::query()->create([
                    'title' => $model['title'],
                    'car_mark_id' => $mark->id,
                ]);
                $modelsCount++;
            }
        }

        return count($makes);
    }

    private function importPartCategories(array $payload): int
    {
        usort($payload, static function (array $left, array $right): int {
            return ($left['parent_id'] <=> $right['parent_id']) ?: ($left['title'] <=> $right['title']);
        });

        $idMap = [];

        foreach ($payload as $category) {
            $parentId = $category['parent_id'] ? ($idMap[$category['parent_id']] ?? null) : null;

            $new = PartCategory::query()->create([
                'title' => $category['title'],
                'parent_id' => $parentId,
            ]);

            $idMap[$category['id']] = $new->id;
        }

        return count($payload);
    }

    /**
     * @param class-string $modelClass
     */
    private function importSimpleTable(string $modelClass, array $payload): int
    {
        foreach ($payload as $item) {
            $modelClass::query()->create([
                'title' => $item['title'],
            ]);
        }

        return count($payload);
    }

    private function readJson(string $path): array
    {
        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException("Nepavyko perskaityti failo: {$path}");
        }

        $decoded = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            throw new RuntimeException("Sugadintas JSON failas: {$path}");
        }

        return $decoded;
    }

    private function truncateTables(): void
    {
        Schema::disableForeignKeyConstraints();

        PartCategory::query()->truncate();
        CarModel::query()->truncate();
        CarMark::query()->truncate();
        FuelType::query()->truncate();
        BodyType::query()->truncate();
        Engine::query()->truncate();

        Schema::enableForeignKeyConstraints();
    }
}

