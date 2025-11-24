<?php

namespace App\Filament\Resources\DamageCases\Pages;

use App\Filament\Resources\DamageCases\DamageCaseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDamageCase extends ViewRecord
{
    protected static string $resource = DamageCaseResource::class;

    public function getHeading(): string
    {
        $number = $this->record->damage_number ?? 'Gedimų atvejis';
        $client = trim(($this->record->first_name ?? '') . ' ' . ($this->record->last_name ?? ''));
        if (!empty($client)) {
            return "{$number} - {$client}";
        }
        return $number;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
