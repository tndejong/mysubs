<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PrepaidCard extends Model
{
    /**
     * Fillables
     */
    protected $fillable = [
        'organisation_id',
        'card_number',
        'member_id',
        'balance',
        'activated_at',
        'expired_at'
    ];

    /**
     * Static boot on creating add random card number uuid
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($prepaidCard) {
            $prepaidCard->card_number = Str::uuid();
            $prepaidCard->organisation_id = Filament::getTenant()->id;
        });
    }

    /**
     * Relation with organisation
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Relation with member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
