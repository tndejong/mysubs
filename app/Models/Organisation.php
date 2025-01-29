<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Organisation extends Model implements HasMedia, HasAvatar, HasCurrentTenantLabel
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
    ];

    /**
     * Appends logo url to model.
     */
    protected $appends = [
        'logo',
    ];

    /**
     * On creation make slug from name
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($organisation) {
            $organisation->slug = Str::slug($organisation->name);
        });

        static::updating(function ($organisation) {
            $organisation->slug = Str::slug($organisation->name);
        });
    }

    /**
     * Get organisation avater
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->logo;
    }

    /**
     * Current tenant label
     */
    public function getCurrentTenantLabel(): string
    {
        return 'Active';
    }

    /**
     * Belongs to many users
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Belongs to many members
     */
    public function members()
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Has Many roles
     */
    public function roles()
    {
        return $this->hasMany(Role::class, 'organisation_id');
    }

    /**
     * Has many prepaid cards
     */
    public function prepaidCards()
    {
        return $this->hasMany(PrepaidCard::class, 'organisation_id');
    }

    /**
     * Make attribute to retrieve logo url from media library 'logo' conversion.
     */
    public function logo(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl('logo') ?? ''
        );
    }

    /**
     * Register logo media collection.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
                ->singleFile();
    }

    /**
     * Register the media conversion on upload.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('logo')
              ->width(45)
              ->height(45)
              ->sharpen(4);
    }
}
