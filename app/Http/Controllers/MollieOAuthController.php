<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class MollieOAuthController extends Controller
{
    /**
     * Redirect to mollie login screen
     */
    public function redirectToMollie(Request $request)
    {
        // Get auth user organisations and check if one of them is the tenant
        $organisations = Auth::user()->organisations;
        $organisation = $organisations->firstWhere('id', $request->tenant);

        if (!$organisation) {
            Notification::make()
                ->title('Organisatie niet gevonden')
                ->body('De gevraagde organisatie is niet gevonden.')
                ->danger()
                ->send();
            return redirect()->route('filament.pages.edit-organisation-profile');
        }

        return $organisation->redirectToMollie();
    }
}
