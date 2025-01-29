<?php

namespace App\Models;

use Filament\Facades\Filament;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Member extends Model implements HasTenants
{
    /**
     * Fillables
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'date_of_birth',
        'phone_number',
        'postcode',
        'street',
        'city',
        'country',
    ];

    /**
     * Creating model
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->hasUser()) {
                unset($model->organisation_id);
            }
        });

        static::created(function ($model) {
            if (auth()->hasUser()) {
                $currentTenant = Filament::getTenant()->id;
                $model->organisations()->attach($currentTenant);
            }
        });
    }

    /**
     * Belongs to many organisations
     */
    public function organisations()
    {
        return $this->belongsToMany(Organisation::class);
    }


    /**
     * Has Many prepaid cards
     */
    public function prepaidCards()
    {
        return $this->hasMany(PrepaidCard::class);
    }

    /**
     * Get the tenants associated with the user
     */
    public function getTenants(Panel $panel): Collection
    {
        return $this->organisations;
    }

    /**
     * Can access tenant
     */
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->organisations()->whereKey($tenant)->exists();
    }
}
