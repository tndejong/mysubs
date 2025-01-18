<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Organisation extends Model implements HasMedia
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
     * Relation with owners of organisation.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Make attribute to retrieve logo url from media library 'logo' conversion.
     */
    public function logo(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getMedia()->firstWhere('name', 'logo')->getUrl('logo')
        );
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
