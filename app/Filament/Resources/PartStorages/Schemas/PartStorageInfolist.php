<?php

namespace App\Filament\Resources\PartStorages\Schemas;

use App\Models\PartStorage as PartStorageModel;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartStorageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalės informacija')
                    ->description('Pagrindiniai sandėlyje esančios detalės duomenys')
                    ->columns(2)
                    ->components([
                        TextEntry::make('part_number')
                            ->label('Detalės numeris')
                            ->placeholder('Nenurodytas'),
                        TextEntry::make('vin_code')
                            ->label('VIN kodas')
                            ->badge()
                            ->color('success')
                            ->placeholder('Nenurodytas'),
                        TextEntry::make('partCategory.title')
                            ->label('Kategorija')
                            ->badge()
                            ->color('primary'),
                        TextEntry::make('year')
                            ->label('Metai')
                            ->badge()
                            ->color('warning')
                            ->placeholder('Nenurodyta'),
                        TextEntry::make('quantity')
                            ->label('Kiekis')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('notes')
                            ->label('Pastabos')
                            ->columnSpanFull()
                            ->placeholder('Nėra papildomos informacijos'),
                    ]),
                Section::make('Suderinamumas')
                    ->description('Susiejimai su transporto priemonėmis ir parametrais')
                    ->columns(2)
                    ->components([
                        TextEntry::make('carMark.title')
                            ->label('Markė')
                            ->badge()
                            ->color('info')
                            ->placeholder('Nenurodyta'),
                TextEntry::make('carModel.title')
                            ->label('Modelis')
                            ->placeholder('Nenurodytas'),
                TextEntry::make('engine.title')
                            ->label('Variklis')
                            ->placeholder('Nenurodytas'),
                TextEntry::make('fuelType.title')
                            ->label('Kuro tipas')
                            ->placeholder('Nenurodytas'),
                TextEntry::make('bodyType.title')
                            ->label('Kėbulo tipas')
                            ->placeholder('Nenurodytas'),
                    ]),
                Section::make('Nuotraukos')
                    ->description('Detalės nuotraukos')
                    ->components([
                        ViewEntry::make('images_gallery')
                            ->label('Nuotraukos')
                            ->view('filament.part-storages.infolist.images-gallery')
                            ->viewData(fn (PartStorageModel $record) => [
                                'images' => $record->images()
                                    ->orderBy('sort_order')
                                    ->get()
                                    ->map(fn ($image) => [
                                        'id' => $image->id,
                                        'url' => $image->path ? route('files.private.show', ['path' => $image->path]) : null,
                                        'name' => $image->original_name ?? basename($image->path ?? ''),
                                        'uploaded_at' => optional($image->created_at)->format('Y-m-d H:i'),
                                    ])
                                    ->filter(fn ($image) => filled($image['url']))
                                    ->values(),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Sistemos informacija')
                    ->description('Įrašo sukurimo ir atnaujinimo datos')
                    ->columns(2)
                    ->components([
                TextEntry::make('created_at')
                            ->label('Sukurta')
                            ->dateTime('Y-m-d H:i')
                            ->placeholder('Nenurodyta'),
                TextEntry::make('updated_at')
                            ->label('Atnaujinta')
                            ->dateTime('Y-m-d H:i')
                            ->placeholder('Nenurodyta'),
                    ]),
            ]);
    }
}
