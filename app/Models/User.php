<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Invoice;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'banned',
    ];

    // Correct: casts comme propriété, pas méthode
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'banned' => 'boolean', // <- ici le champ exact
    ];

    /*
    |--------------------------------------------------------------------------
    | Sécurité & Statut
    |--------------------------------------------------------------------------
    */
    public function isBanned(): bool
    {
        return (bool) $this->banned;
    }

    /*
    |--------------------------------------------------------------------------
    | Rôles
    |--------------------------------------------------------------------------
    */
    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    public function isMedecin(): bool
    {
        return $this->role === 'medecin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /*
    |--------------------------------------------------------------------------
    | Relations Rendez-vous
    |--------------------------------------------------------------------------
    */
    public function patientAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function medecinAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'medecin_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Relations Dossiers Médicaux
    |--------------------------------------------------------------------------
    */
    public function medicalRecordsAsPatient(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'patient_id');
    }

    public function medicalRecordsAsMedecin(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'medecin_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'patient_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
