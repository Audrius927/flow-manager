<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\PasswordReset\ResetPassword as BaseResetPassword;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;

class ResetPassword extends BaseResetPassword
{
    public function getSubheading(): string | \Illuminate\Contracts\Support\Htmlable | null
    {
        // Remove the login action from subheading to avoid duplicate buttons
        return null;
    }

    public function getFormContentComponent(): \Filament\Schemas\Components\Component
    {
        return \Filament\Schemas\Components\Form::make([
            \Filament\Schemas\Components\EmbeddedSchema::make('form')
                ->schema([
                    TextInput::make('email')
                        ->label('El. paštas')
                        ->email()
                        ->required()
                        ->disabled(),
                    TextInput::make('password')
                        ->label('Slaptažodis')
                        ->password()
                        ->required()
                        ->minLength(8)
                        ->revealable(),
                    TextInput::make('password_confirmation')
                        ->label('Patvirtinkite slaptažodį')
                        ->password()
                        ->required()
                        ->minLength(8)
                        ->revealable()
                        ->same('password'),
                ])
        ])
            ->id('form')
            ->livewireSubmitHandler('resetPassword')
            ->footer([
                \Filament\Schemas\Components\Actions::make($this->getFormActions())
                    ->alignment($this->getFormActionsAlignment())
                    ->fullWidth($this->hasFullWidthFormActions()),
                // Move the login action to the bottom of the form
                \Filament\Schemas\Components\Actions::make([$this->loginAction()])
                    ->alignment('center')
                    ->fullWidth(false),
            ]);
    }

    public function loginAction(): Action
    {
        return Action::make('login')
            ->link()
            ->label('Grįžti į prisijungimą')
            ->icon('heroicon-o-arrow-left')
            ->url(filament()->getLoginUrl());
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('reset')
                ->label('Atkurti slaptažodį')
                ->submit('resetPassword')
                ->keyBindings(['mod+s']),
        ];
    }
}
