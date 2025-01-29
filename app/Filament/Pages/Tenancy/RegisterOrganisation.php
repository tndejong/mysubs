<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Organisation;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterOrganisation extends RegisterTenant
{
    /**
     * Get the label of the registration form.
     */
    public static function getLabel(): string
    {
        return 'Register organisation';
    }

    /**
     * Get the schema of the registration form.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                Select::make('plan')->options([
                    'free' => 'Free',
                    'basic' => 'Basic',
                    'premium' => 'Premium',
                ]),
            ]);
    }

    /**
     * Handle the registration of the organisation.
     */
    protected function handleRegistration(array $data): Organisation
    {
        /** @var User $user */
        $user = auth()->user();
        $organisation = Organisation::create($data);
        $organisation->users()->attach($user);

        $this->makeSuperAdmin(
            organisation: $organisation,
            user: $user,
        );

        return $organisation;
    }

    /**
     * Make the authenticated user a super admin of the organisation.
     */
    private function makeSuperAdmin(Organisation $organisation, User $user): void
    {
        // Create Role super admin for organisation
        $role = Role::create([
            'name' => 'super_admin',
            'organisation_id' => $organisation->id,
            'guard_name' => 'web',
        ]);

        // Attach role to user
        $user->roles()->attach($role->id);
    }
}
