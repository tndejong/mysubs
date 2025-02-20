<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;
use Illuminate\Support\Facades\Auth;

class EditOrganisationProfile extends EditTenantProfile
{
    /**
     * Get the label of the organisation
     */
    public static function getLabel(): string
    {
        return 'Organisation profile';
    }

    /**
     * Get the form for the organisation
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                SpatieMediaLibraryFileUpload::make('logo')->collection('logo')->conversion('logo')
            ]);
    }

    /**
     * Definieer de acties voor het formulier.
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('Koppel Mollie-account')
                ->label('Koppel Mollie-account')
                ->color('primary')
                ->action(fn () => redirect()->route('mollie.redirect', ['tenant' => Filament::getTenant()]))
                ->visible(fn () => !Auth::user()->latestOrganisation->isMollieAuthenticated()),
        ];
    }

    /**
     * Handel de Mollie OAuth-callback af.
     */
    public function handleMollieCallback()
    {
        // Handle the Mollie callback
        Auth::user()->latestOrganisation->handleMollieCallback();
        return redirect()->route('filament.app.tenant.profile', ['tenant' => Auth::user()->latestOrganisation->slug]);
    }
}
