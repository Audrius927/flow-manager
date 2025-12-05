<?php

namespace App\Filament\Resources\PartStorages\Pages;

use App\Filament\Resources\PartStorages\PartStorageResource;
use App\Models\PartStorageImage;
use Filament\Resources\Pages\CreateRecord;

class CreatePartStorage extends CreateRecord
{
    protected static string $resource = PartStorageResource::class;

    protected array $imagesUploads = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->imagesUploads = $data['images_uploads'] ?? [];

        unset($data['images_uploads']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->syncImages($this->imagesUploads);
    }

    protected function syncImages(array $paths): void
    {
        if (empty($paths)) {
            return;
        }

        foreach ($paths as $index => $path) {
            $this->record->images()->create([
                'disk' => 'private',
                'path' => $path,
                'original_name' => basename($path),
                'sort_order' => $index,
            ]);
        }
    }
}
