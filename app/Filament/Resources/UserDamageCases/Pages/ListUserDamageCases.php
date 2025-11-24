<?php

namespace App\Filament\Resources\UserDamageCases\Pages;

use App\Filament\Resources\UserDamageCases\UserDamageCaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserDamageCases extends ListRecords
{
    protected static string $resource = UserDamageCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Priskirti vartotoją')
                ->modalHeading('Priskirti vartotoją gedimų atvejui')
                ->modalSubmitActionLabel('Priskirti')
                ->modalCancelActionLabel('Atšaukti')
                ->mutateFormDataUsing(function (array $data): array {
                    // Konvertuojame user_ids į masyvą, jei reikia
                    if (isset($data['user_ids']) && !is_array($data['user_ids'])) {
                        $data['user_ids'] = [$data['user_ids']];
                    }
                    return $data;
                })
                ->createAnother(false)
                ->action(function (array $data) {
                    $damageCaseId = $data['damage_case_id'];
                    $userIds = $data['user_ids'] ?? [];
                    
                    if (empty($userIds)) {
                        \Filament\Notifications\Notification::make()
                            ->title('Klaida')
                            ->body('Pasirinkite bent vieną vartotoją.')
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    $created = 0;
                    foreach ($userIds as $userId) {
                        // Patikrinti ar jau egzistuoja
                        $exists = \App\Models\UserDamageCase::where('user_id', $userId)
                            ->where('damage_case_id', $damageCaseId)
                            ->exists();
                        
                        if (!$exists) {
                            \App\Models\UserDamageCase::create([
                                'user_id' => $userId,
                                'damage_case_id' => $damageCaseId,
                            ]);
                            $created++;
                        }
                    }
                    
                    if ($created > 0) {
                        \Filament\Notifications\Notification::make()
                            ->title('Sėkmė')
                            ->body("Sėkmingai priskirta {$created} vartotojų.")
                            ->success()
                            ->send();
                    } else {
                        \Filament\Notifications\Notification::make()
                            ->title('Informacija')
                            ->body('Visi pasirinkti vartotojai jau buvo priskirti.')
                            ->info()
                            ->send();
                    }
                })
                ->successNotificationTitle('Vartotojai sėkmingai priskirti'),
        ];
    }

    public function getHeading(): string
    {
        return 'Vartotojų priskyrimai';
    }
}
