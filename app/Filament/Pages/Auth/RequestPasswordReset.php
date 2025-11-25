<?php

namespace App\Filament\Pages\Auth;

use App\Filament\Notifications\ResetPassword;
use Filament\Auth\Pages\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Password;

class RequestPasswordReset extends BaseRequestPasswordReset
{
    public function getSubheading(): string | \Illuminate\Contracts\Support\Htmlable | null
    {
        return null;
    }

    protected function getRateLimitedNotification(\DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException $exception): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->title('Per daug bandymų') // lang/lt/filament-panels/auth/pages/password-reset/request-password-reset.php
            ->body("Bandykite dar kartą už {$exception->secondsUntilAvailable} sekundžių.")
            ->danger();
    }

    protected function getSentNotification(string $status): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->title('Slaptažodžio atkūrimo nuorodą išsiuntėme el. paštu')
            ->body('Jei jūsų el. pašto adresas yra mūsų sistemoje, jums bus išsiųstas slaptažodžio atkūrimo el. laiškas.')
            ->success();
    }

    public function getFormContentComponent(): \Filament\Schemas\Components\Component
    {
        return \Filament\Schemas\Components\Form::make([\Filament\Schemas\Components\EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('request')
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

    /**
     * Get the notification class to use for password reset.
     */
    protected function getPasswordResetNotificationClass(): string
    {
        return ResetPassword::class;
    }

    /**
     * Override the request method to use our custom notification.
     */
    public function request(): void
    {
        try {
            $this->rateLimit(5);
        } catch (\DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();
            return;
        }

        $data = $this->form->getState();

        $status = Password::sendResetLink(
            $data,
            function ($user, $token) {
                $notification = app(ResetPassword::class, ['token' => $token]);
                $notification->url = filament()->getResetPasswordUrl($token, $user);
                
                $user->notify($notification);
            }
        );

        if ($status === Password::RESET_LINK_SENT) {
            $this->getSentNotification($status)?->send();
        } elseif ($status === Password::INVALID_USER) {
            // Vartotojas su tokiu email neegzistuoja, bet vis tiek rodomas success pranešimas
            // dėl saugumo (nes nenorime atskleisti, kurie email egzistuoja)
            $this->getSentNotification($status)?->send();
        } else {
            // Kitų atvejų neturėtų būti su sendResetLink, bet jei atsiranda - log
            \Log::warning('Unexpected password reset status: ' . $status);
        }
    }
}
