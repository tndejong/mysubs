<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Collection;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements HasTenants, FilamentUser, HasDefaultTenant
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'latest_organisation_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Determine if the user can access the given panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Implement your logic to determine if the user can access the panel
        return true;
    }

    /**
     * Belongs to many organisations
     */
    public function organisations(): BelongsToMany
    {
        return $this->belongsToMany(Organisation::class);
    }

    /**
     * Get the tenants associated with the user
     */
    public function getTenants(Panel $panel): Collection
    {
        return $this->organisations;
    }

    /**
     * Get the default tenant
     */
    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->latestOrganisation;
    }

    /**
     * Get the latest organisation
     */
    public function latestOrganisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'latest_organisation_id');
    }

    /**
     * Can access tenant
     */
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->organisations()->whereKey($tenant)->exists();
    }
}
