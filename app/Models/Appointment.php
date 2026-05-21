<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['pet_id', 'user_id', 'scheduled_at', 'reason', 'notes', 'status', 'reminder_sent'])]
class Appointment extends Model
{
    use HasFactory;

    /**
     * Reason labels in Spanish.
     */
    public static array $reasons = [
        'consulta_general'         => 'Consulta General',
        'vacunacion'               => 'Vacunación',
        'cirugia'                  => 'Cirugía',
        'revision_post_operatoria' => 'Revisión Post-Operatoria',
        'urgencia'                 => 'Urgencia',
        'otro'                     => 'Otro',
    ];

    /**
     * Status labels in Spanish.
     */
    public static array $statuses = [
        'pendiente'  => 'Pendiente',
        'confirmada' => 'Confirmada',
        'completada' => 'Completada',
        'cancelada'  => 'Cancelada',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'scheduled_at'  => 'datetime',
            'reminder_sent' => 'boolean',
        ];
    }

    /**
     * Get the pet associated with this appointment.
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Get the veterinarian (user) assigned to this appointment.
     */
    public function veterinarian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the notification logs for this appointment.
     */
    public function notificationLogs(): HasMany
    {
        return $this->hasMany(NotificationLog::class);
    }
}
