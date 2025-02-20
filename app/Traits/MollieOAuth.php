<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

trait MollieOAuth
{
    /**
     * Start de OAuth-authenticatie.
     */
    public function redirectToMollie(): RedirectResponse
    {
        $scopes = ['payments.read', 'payments.write'];

        try {
            $response = Socialite::driver('mollie')->scopes($scopes);
            return $response->redirect();
        } catch (\Exception $e) {
            Log::withContext([
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ])->error('Error redirecting to mollie');
        }
    }

    /**
     * Handel de callback van Mollie af.
     */
    public function handleMollieCallback()
    {
        try {
            $mollieUser = Socialite::driver('mollie')->user();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error handling Mollie callback')
                ->danger()
                ->send();

            Log::withContext([
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ])->error('Error handling Mollie callback');
            return;
        }

        // Save the Mollie tokens on the parent model
        $this->mollie_access_token = $mollieUser->token;
        $this->mollie_refresh_token = $mollieUser->refreshToken;
        $this->mollie_token_expires_at = now()->addSeconds($mollieUser->expiresIn);
        $this->save();

        // Feedback aan de gebruiker
        Notification::make()
            ->title('Mollie account succesvol gekoppeld.')
            ->success()
            ->send();
    }

    /**
     * Verifieer of de Mollie-authenticatie geldig is.
     */
    public function isMollieAuthenticated(): bool
    {
        $date = Carbon::parse($this->mollie_token_expires_at);
        return $this->mollie_access_token && $date->isFuture();
    }

    /**
     * Vernieuw de Mollie access token indien verlopen.
     */
    public function refreshMollieToken()
    {
        if ($this->mollie_token_expires_at->isPast()) {
            $response = Http::asForm()->post('https://api.mollie.com/oauth2/tokens', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->mollie_refresh_token,
                'client_id' => config('services.mollie.client_id'),
                'client_secret' => config('services.mollie.client_secret'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->mollie_access_token = $data['access_token'];
                $this->mollie_refresh_token = $data['refresh_token'];
                $this->mollie_token_expires_at = now()->addSeconds($data['expires_in']);
                $this->save();
            } else {
                // Foutafhandeling
                session()->flash('error', 'Het vernieuwen van de Mollie token is mislukt.');
            }
        }
    }
}