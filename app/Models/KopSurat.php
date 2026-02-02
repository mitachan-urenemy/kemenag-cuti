<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KopSurat extends Model
{
    /**
     * Get the surats for the KopSurat.
     */
    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class);
    }
}
