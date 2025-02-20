<?php

use App\Filament\Pages\Tenancy\EditOrganisationProfile;
use App\Http\Controllers\MollieOAuthController;
use Illuminate\Support\Facades\Route;

Route::get('mollie/redirect', [MollieOAuthController::class, 'redirectToMollie'])
    ->name('mollie.redirect');

Route::get('mollie/callback', [EditOrganisationProfile::class, 'handleMollieCallback'])
    ->name('mollie.callback');
