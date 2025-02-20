<?php

namespace App\Listeners;

use Filament\Events\TenantSet;
use Illuminate\Support\Facades\Log;

class UpdateLatestTenantIdListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        Log::info('UpdateLatestTenantId::__construct()');
    }

    /**
     * Handle the event.
     */
    public function handle(TenantSet $event): void
    {
        // get the user from the event
        $user = $event->getUser();

        // get the tenant (team) from the event
        $organisation = $event->getTenant();

        // update the user's latest_team_id
        $user->latest_organisation_id = $organisation->id;
        $user->save();
    }
}
