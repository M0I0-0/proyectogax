<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    /**
     * Get the medical records for this pet.
     */
    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class)->latest();
    }

    /**
     * Get the vaccinations for this pet.
     */
    public function vaccinations(): HasMany
    {
        return $this->hasMany(Vaccination::class)->orderByDesc('date_applied');
    }

    /**
     * Get the appointments scheduled for this pet.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class)->orderBy('scheduled_at');
    }
}
