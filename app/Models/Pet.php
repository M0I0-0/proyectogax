<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['owner_id', 'name', 'species', 'breed', 'birthdate', 'weight', 'photo'])]
class Pet extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the owner that owns this pet.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }
}
