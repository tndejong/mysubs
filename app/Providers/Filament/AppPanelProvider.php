<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Tenancy\EditOrganisationProfile;
use App\Filament\Pages\Tenancy\RegisterOrganisation;
use App\Models\Organisation;
use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\Middleware\SyncShieldTenant;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    /**
     * Default panel configuration.
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('/')
            ->login()
            ->colors([
                'primary' => Color::Stone,
            ])
            ->brandLogo(fn () => view('mysubs-logo'))
            ->tenantMenu(true)
            ->tenant(Organisation::class, ownershipRelationship: 'organisation', slugAttribute: 'slug')
            ->tenantMiddleware([
                SyncShieldTenant::class,
            ], isPersistent: true)
            ->tenantRegistration(RegisterOrganisation::class)
            ->tenantProfile(EditOrganisationProfile::class)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->viteTheme('resources/css/filament/app/theme.css')
            ->darkMode(false)
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
