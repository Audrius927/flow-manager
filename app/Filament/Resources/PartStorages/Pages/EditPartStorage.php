<?php

namespace App\Filament\Resources\PartStorages\Pages;

use App\Filament\Resources\PartStorages\PartStorageResource;
use App\Models\PartStorageImage;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPartStorage extends EditRecord
{
    protected static string $resource = PartStorageResource::class;

    protected array $imagesUploads = [];

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->imagesUploads = $data['images_uploads'] ?? [];

        unset($data['images_uploads']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->syncImages($this->imagesUploads);
    }

    protected function syncImages(array $paths): void
    {
        $currentPaths = $this->record->images()->pluck('path')->all();

        $paths = array_values(array_filter($paths));

        // Pašalinti nuotraukas, kurios buvo pašalintos iš formos
        $toDelete = array_diff($currentPaths, $paths);
        if (!empty($toDelete)) {
            $this->record->images()->whereIn('path', $toDelete)->delete();
        }

        // Pridėti naujas nuotraukas ir atnaujinti sort_order
        foreach ($paths as $index => $path) {
            $image = $this->record->images()->where('path', $path)->first();
            
            if ($image) {
                // Atnaujinti sort_order, jei nuotrauka jau egzistuoja
                $image->update(['sort_order' => $index]);
            } else {
                // Sukurti naują nuotrauką
                $this->record->images()->create([
                    'disk' => 'private',
                    'path' => $path,
                    'original_name' => basename($path),
                    'sort_order' => $index,
                ]);
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
