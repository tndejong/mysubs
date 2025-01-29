<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * Has relation with multiple organisations
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }
}
