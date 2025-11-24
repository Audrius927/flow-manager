<?php

namespace App\Filament\Resources\DamageCases\Pages;

use App\Filament\Resources\DamageCases\DamageCaseResource;
use App\Models\DamageCaseDocument;
use App\Models\DamageCasePhoto;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDamageCase extends EditRecord
{
    protected static string $resource = DamageCaseResource::class;

    protected array $documentsUploads = [];

    protected array $photosUploads = [];

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->documentsUploads = $data['documents_uploads'] ?? [];
        $this->photosUploads = $data['photos_uploads'] ?? [];

        unset($data['documents_uploads'], $data['photos_uploads']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->syncFiles(DamageCaseDocument::class, 'documents', $this->documentsUploads);
        $this->syncFiles(DamageCasePhoto::class, 'photos', $this->photosUploads);
    }

    protected function syncFiles(string $modelClass, string $relation, array $paths): void
    {
        $currentPaths = $this->record->{$relation}()->pluck('path')->all();

        $paths = array_values(array_filter($paths));

        $toDelete = array_diff($currentPaths, $paths);
        if (!empty($toDelete)) {
            $this->record->{$relation}()->whereIn('path', $toDelete)->delete();
        }

        $toCreate = array_diff($paths, $currentPaths);
        foreach ($toCreate as $path) {
            $this->record->{$relation}()->create([
                'disk' => 'private',
                'path' => $path,
                'original_name' => basename($path),
            ]);
        }
    }

    public function getHeading(): string
    {
        return 'Redaguoti užsakymą';
    }

    public function getTitle(): string
    {
        return 'Redaguoti užsakymą';
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
