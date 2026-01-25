<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\MedicalRecord;
use App\Models\Invoice;

class Appointment extends Model
{
    /**
     * Champs autorisés à l’assignation de masse
     */
    protected $fillable = [
        'patient_id',
        'medecin_id',
        'appointment_at',
        'status',

        'reason',
        'type',
        'notes_patient',
        'notes_medecin',
        'duration',
        'confirmed_at',
        'cancel_reason',
    ];

    /**
     * Casts automatiques
     */
    protected $casts = [
        'appointment_at' => 'datetime',
        'confirmed_at'   => 'datetime',
        'duration'       => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    // Patient
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Médecin
    public function medecin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    // Dossier médical lié au rendez-vous
    public function medicalRecord(): HasOne
    {
        return $this->hasOne(MedicalRecord::class);
    }

    // 💳 Facture liée au rendez-vous
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
