<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

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
                SpatieMediaLibraryFileUpload::make('logo')->collection('logo')->conversion('logo'),
            ]);
    }
}
