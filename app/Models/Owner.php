<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'email', 'phone', 'address'])]
class Owner extends Model
{
    use HasFactory;

    /**
     * Get the pets owned by this owner.
     */
    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }
}
