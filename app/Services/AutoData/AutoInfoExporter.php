<?php

namespace App\Services\AutoData;

use App\Repositories\AutoInfoRepository;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class AutoInfoExporter
{
    public function __construct(
        private readonly AutoInfoRepository $repository,
    ) {
    }

    /**
     * Eksportuoja pirminius duomenis į atskirus JSON failus.
     *
     * @return array<int, string> Pilni sugeneruotų failų keliai
     */
    public function export(string $directory = 'auto-data', string $diskName = 'local'): array
    {
        $disk = Storage::disk($diskName);
        $directory = trim($directory, '/');

        if ($directory === '') {
            throw new RuntimeException('Eksporto katalogas negali būti tuščias.');
        }

        if (!$disk->exists($directory)) {
            $disk->makeDirectory($directory);
        }

        $files = [
            'car_marks.json' => $this->formatCarMarks(),
            'part_categories.json' => $this->formatPartCategories(),
            'fuel_types.json' => $this->formatSimpleCollection($this->repository->getFuelTypes()),
            'body_types.json' => $this->formatSimpleCollection($this->repository->getBodyTypes()),
            'engines.json' => $this->formatSimpleCollection($this->repository->getEngines()),
        ];

        $exportedPaths = [];

        foreach ($files as $fileName => $payload) {
            $relativePath = "{$directory}/{$fileName}";
            $disk->put(
                $relativePath,
                json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            $exportedPaths[] = $disk->path($relativePath);
        }

        return $exportedPaths;
    }

    private function formatCarMarks(): array
    {
        return $this->repository->getCarMarksWithModels()
            ->map(fn ($mark) => [
                'id' => $mark->id,
                'title' => $mark->title,
                'models' => $mark->models->map(fn ($model) => [
                    'id' => $model->id,
                    'title' => $model->title,
                ])->values()->all(),
            ])
            ->values()
            ->all();
    }

    private function formatPartCategories(): array
    {
        return $this->repository->getPartCategories()
            ->map(fn ($category) => [
                'id' => $category->id,
                'title' => $category->title,
                'parent_id' => $category->parent_id,
            ])
            ->values()
            ->all();
    }

    private function formatSimpleCollection($collection): array
    {
        return $collection
            ->map(fn ($item) => [
                'id' => $item->id,
                'title' => $item->title,
            ])
            ->values()
            ->all();
    }
}

