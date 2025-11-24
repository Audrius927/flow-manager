<?php

namespace App\Filament\Resources\DamageCases\Pages;

use App\Filament\Resources\DamageCases\DamageCaseResource;
use App\Models\DamageCaseDocument;
use App\Models\DamageCasePhoto;
use Filament\Resources\Pages\CreateRecord;

class CreateDamageCase extends CreateRecord
{
    protected static string $resource = DamageCaseResource::class;

    protected static bool $canCreateAnother = false;

    protected array $documentsUploads = [];

    protected array $photosUploads = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->documentsUploads = $data['documents_uploads'] ?? [];
        $this->photosUploads = $data['photos_uploads'] ?? [];

        unset($data['documents_uploads'], $data['photos_uploads']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->syncFiles(DamageCaseDocument::class, 'documents', $this->documentsUploads);
        $this->syncFiles(DamageCasePhoto::class, 'photos', $this->photosUploads);
    }

    protected function syncFiles(string $modelClass, string $relation, array $paths): void
    {
        if (empty($paths)) {
            return;
        }

        foreach ($paths as $path) {
            $this->record->{$relation}()->create([
                'disk' => 'private',
                'path' => $path,
                'original_name' => basename($path),
            ]);
        }
    }

    public function getHeading(): string
    {
        return 'Naujas užsakymas';
    }

    public function getTitle(): string
    {
        return 'Naujas užsakymas';
    }
}
