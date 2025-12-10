<?php

namespace App\Filament\Resources\DamageCases\Schemas;

use App\Models\DamageCase;
use App\Services\DamageCases\DamageCaseFieldPermissionResolver;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DamageCaseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $user = auth()->user();
        $permissions = app(DamageCaseFieldPermissionResolver::class);
        $canView = fn (string ...$fields): bool => $permissions->canViewAny($user, ...$fields);

        return $schema
            ->components([
                Section::make('Žalos bylos informacija')
                    ->components([
                        TextEntry::make('damage_number')
                            ->label('Žalos nr.')
                            ->badge()
                            ->color('primary')
                            ->copyable()
                            ->copyMessage('Gedimo numeris nukopijuotas')
                            ->visible($canView('damage_number')),
                        TextEntry::make('insuranceCompany.title')
                            ->label('Draudimo kompanija')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('insurance_company')),
                        TextEntry::make('product.title')
                            ->label('Produktas')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Nenurodytas')
                            ->visible($canView('product')),
                        TextEntry::make('productSub.title')
                            ->label('Subproduktas')
                            ->badge()
                            ->color('info')
                            ->placeholder('Nenurodytas')
                            ->visible($canView('product')),
                        TextEntry::make('order_date')
                            ->label('Užsakymo data')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('order_date')),
                        TextEntry::make('received_at')
                            ->label('Perėmimo data / laikas')
                            ->dateTime('Y-m-d H:i')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('received_at')),
                        TextEntry::make('carMark.title')
                            ->label('Markė')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('car_mark_id')),
                        TextEntry::make('carModel.title')
                            ->label('Modelis')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Nenurodytas')
                            ->visible($canView('car_model_id')),
                        TextEntry::make('license_plate')
                            ->label('Valst nr.')
                            ->badge()
                            ->color('info')
                            ->copyable()
                            ->copyMessage('Valstybinis numeris nukopijuotas')
                            ->placeholder('Nenurodytas')
                            ->visible($canView('license_plate')),
                        TextEntry::make('first_name')
                            ->label('Vardas')
                            ->placeholder('Nenurodytas')
                            ->visible($canView('first_name')),
                        TextEntry::make('last_name')
                            ->label('Pavardė')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('last_name')),
                        TextEntry::make('phone')
                            ->label('Tel nr.')
                            ->placeholder('Nenurodytas')
                            ->visible($canView('phone')),
                        TextEntry::make('city.title')
                            ->label('Miestas')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Nenurodytas')
                            ->visible($canView('received_location')),
                        TextEntry::make('received_location')
                            ->label('Perėmimo vieta (adresas)')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('received_location')),
                        TextEntry::make('storage_location')
                            ->label('Saugojimo vieta')
                            ->badge()
                            ->color('info')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('storage_location')),
                        TextEntry::make('removed_from_storage_at')
                            ->label('Išvežtas iš saugojimo vietos (Data)')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('removed_from_storage_at')),
                        TextEntry::make('returned_to_storage_at')
                            ->label('Grąžintas į saugojimo vietą (Data)')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('returned_to_storage_at')),
                        TextEntry::make('returned_to_client_at')
                            ->label('Grąžintas klientui (Data)')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('returned_to_client_at')),
                        TextEntry::make('repairCompany.title')
                            ->label('Remonto įmonė')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('repair_company')),
                        TextEntry::make('planned_repair_start')
                            ->label('Planuojama remonto pradžia (Data)')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('planned_repair_start')),
                        TextEntry::make('planned_repair_end')
                            ->label('Planuojama remonto pabaiga (Data)')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('planned_repair_end')),
                        TextEntry::make('finished_at')
                            ->label('Baigta')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
                            ->visible($canView('finished_at')),
                        ViewEntry::make('photos_gallery')
                            ->label('Nuotraukos')
                            ->view('filament.damage-cases.infolist.photos-gallery')
                            ->viewData(fn (DamageCase $record) => [
                                'photos' => $record->photos()
                                    ->latest()
                                    ->get()
                                    ->map(fn ($photo) => [
                                        'id' => $photo->id,
                                        'url' => $photo->path ? route('files.private.show', ['path' => $photo->path]) : null,
                                        'name' => $photo->original_name ?? basename($photo->path ?? ''),
                                        'uploaded_at' => optional($photo->created_at)->format('Y-m-d H:i'),
                                    ])
                                    ->filter(fn ($photo) => filled($photo['url']))
                                    ->values(),
                            ])
                            ->columnSpanFull()
                            ->visible($canView('photos')),
                        ViewEntry::make('documents_list')
                            ->label('Dokumentai')
                            ->view('filament.damage-cases.infolist.documents-list')
                            ->viewData(fn (DamageCase $record) => [
                                'documents' => $record->documents()
                                    ->latest()
                                    ->get()
                                    ->map(fn ($document) => [
                                        'id' => $document->id,
                                        'url' => $document->path ? route('files.private.show', ['path' => $document->path]) : null,
                                        'name' => $document->original_name ?? basename($document->path ?? ''),
                                        'uploaded_at' => optional($document->created_at)->format('Y-m-d H:i'),
                                    ])
                                    ->filter(fn ($document) => filled($document['url']))
                                    ->values(),
                            ])
                            ->columnSpanFull()
                            ->visible($canView('documents')),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 3,
                        'xl' => 4,
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
