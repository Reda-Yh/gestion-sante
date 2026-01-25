<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Notifications\AppointmentStatusNotification;

class AppointmentController extends Controller
{
    /**
     * Formulaire de création (patient)
     */
    public function create(Request $request)
    {
        $medecins = User::where('role', 'medecin')
            ->select('id', 'name')
            ->get();

        return Inertia::render('Patient/Appointments/Create', [
            'medecins' => $medecins,
            'appointment_at' => $request->appointment_at, // 👈 AJOUT
        ]);
    }


    /**
     * Enregistrer le rendez-vous (patient)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'medecin_id'       => 'required|exists:users,id',
            'appointment_at' => 'required|date|after_or_equal:today',
            'reason'           => 'required|string|min:5',
            'type'             => 'required|in:presentiel,en_ligne',
            'notes_patient'    => 'nullable|string',
            'duration'         => 'nullable|integer|min:10|max:180',
        ]);

        $appointment = Appointment::create([
            'patient_id'     => $request->user()->id,
            'medecin_id'     => $data['medecin_id'],
            'appointment_at' => $data['appointment_at'],
            'reason'         => $data['reason'],
            'type'           => $data['type'],
            'notes_patient'  => $data['notes_patient'] ?? null,
            'duration'       => $data['duration'] ?? 30,
            'status'         => 'en_attente',
        ]);

        // 🔔 Notification au médecin
        $appointment->medecin->notify(
            new AppointmentStatusNotification(
                $appointment,
                'Nouveau rendez-vous demandé par un patient'
            )
        );

        return redirect()
            ->route('patient.dashboard')
            ->with('success', 'Rendez-vous demandé avec succès');
    }

    /**
     * Liste des rendez-vous du patient
     */
    public function indexPatient(Request $request)
    {
        $appointments = $request->user()
            ->patientAppointments()
            ->with(['medecin', 'medicalRecord'])

            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('medecin', function ($m) use ($request) {
                    $m->where('name', 'like', '%' . $request->search . '%');
                });
            })

            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })

            ->when($request->type, function ($q) use ($request) {
                $q->where('type', $request->type);
            })

            ->when($request->date, function ($q) use ($request) {
                $q->whereDate('appointment_at', $request->date);
            })

            ->orderByDesc('appointment_at')
            ->get();

        return Inertia::render('Patient/Appointments/Index', [
            'appointments' => $appointments,
            'filters' => $request->only(['search', 'status', 'type', 'date']),
        ]);
    }

    /**
     * Liste des rendez-vous du médecin
     */
    public function indexMedecin(Request $request)
    {
        $appointments = $request->user()
            ->medecinAppointments()
            ->with(['patient', 'medicalRecord'])
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('patient', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->type, function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->date, function ($query) use ($request) {
                $query->whereDate('appointment_at', $request->date);
            })
            ->orderBy('appointment_at')
            ->get();

        return Inertia::render('Medecin/Appointments/Index', [
            'appointments' => $appointments,
            'filters' => [
                'status' => $request->status,
                'search' => $request->search,
                'type'   => $request->type,
                'date'   => $request->date,
            ],
        ]);
    }

    public function invitations(Request $request)
    {
        $invitations = $request->user()
            ->medecinAppointments()
            ->where('status', 'en_attente')
            ->with(['patient'])
            ->orderBy('appointment_at')
            ->get();

        return Inertia::render('Medecin/Appointments/Invitations', [
            'invitations' => $invitations
        ]);
    }

    /**
     * Confirmer un rendez-vous (médecin)
     */
    public function confirm(Appointment $appointment, Request $request)
    {
        if ($appointment->medecin_id !== $request->user()->id) {
            abort(403);
        }

        $appointment->update([
            'status'       => 'confirme',
            'confirmed_at' => now(),
        ]);

        // 🔔 Notification au patient
        $appointment->patient->notify(
            new AppointmentStatusNotification(
                $appointment,
                'Votre rendez-vous a été confirmé'
            )
        );

        return back()->with('success', 'Rendez-vous confirmé');
    }

    /**
     * Annuler un rendez-vous (médecin)
     */
    public function cancel(Appointment $appointment, Request $request)
    {
        if ($appointment->medecin_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'cancel_reason' => 'required|string|min:5',
        ]);

        $appointment->update([
            'status'        => 'annule',
            'cancel_reason' => $request->cancel_reason,
        ]);

        // 🔔 Notification au patient
        $appointment->patient->notify(
            new AppointmentStatusNotification(
                $appointment,
                'Votre rendez-vous a été annulé'
            )
        );

        return back()->with('success', 'Rendez-vous annulé');
    }

    /**
     * Calendrier médecin
     */
    public function calendar(Request $request)
    {
        $appointments = $request->user()
            ->medecinAppointments()
            ->with('patient')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id'    => $appointment->id,
                    'title' => $appointment->patient->name . ' - ' . ($appointment->reason ?? ''),
                    'start' => $appointment->appointment_at,
                    'end'   => $appointment->appointment_at->addMinutes($appointment->duration ?? 30),
                    'color' => match ($appointment->status) {
                        'confirme'   => '#16a34a',
                        'annule'     => '#dc2626',
                        default      => '#f97316',
                    },
                    'url' => route('medecin.appointments.index'),
                ];
            });

        return Inertia::render('Medecin/Appointments/Calendar', [
            'events' => $appointments,
        ]);
    }

    /**
     * Calendrier patient
     */
    public function calendarPatient(Request $request)
    {
        $appointments = $request->user()
            ->patientAppointments()
            ->with('medecin')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id'    => $appointment->id,
                    'title' => $appointment->medecin->name . ' - ' . ($appointment->reason ?? ''),
                    'start' => $appointment->appointment_at,
                    'end'   => $appointment->appointment_at->addMinutes($appointment->duration ?? 30),
                    'color' => match ($appointment->status) {
                        'confirme'   => '#16a34a',
                        'annule'     => '#dc2626',
                        default      => '#f97316',
                    },
                ];
            });

        return Inertia::render('Patient/Appointments/Calendar', [
            'events' => $appointments,
        ]);
    }

    public function updateTime(Appointment $appointment, Request $request)
    {
        if ($appointment->medecin_id !== $request->user()->id) {
            abort(403);
        }

        if ($appointment->status === 'annule') {
            abort(403);
        }

        $request->validate([
            'appointment_at' => 'required|date',
        ]);

        $appointment->update([
            'appointment_at' => $request->appointment_at,
        ]);

        return back()->with('success', 'Horaire mis à jour');
    }

    public function destroy(Appointment $appointment, Request $request)
    {
        // Sécurité : seulement le médecin propriétaire
        if ($appointment->medecin_id !== $request->user()->id) {
            abort(403);
        }

        // Interdire si dossier médical existe
        if ($appointment->medicalRecord) {
            return back()->withErrors([
                'delete' => 'Impossible de supprimer un rendez-vous avec un dossier médical.'
            ]);
        }

        $appointment->delete();

        return back()->with('success', 'Rendez-vous supprimé');
    }

    public function timeline(Appointment $appointment, Request $request)
    {
        $user = $request->user();

        // Sécurité : patient OU médecin
        if ($appointment->patient_id !== $user->id && $appointment->medecin_id !== $user->id) {
            abort(403);
        }

        // Charger la relation patient
        $appointment->load('patient');

        $events = [];

        // 🟠 Création
        $events[] = [
            'label' => 'Rendez-vous créé',
            'date'  => $appointment->created_at,
            'icon'  => '🟠',
        ];

        // 🟢 Confirmation
        if ($appointment->confirmed_at) {
            $events[] = [
                'label' => 'Rendez-vous confirmé',
                'date'  => $appointment->confirmed_at,
                'icon'  => '🟢',
            ];
        }

        // ❌ Annulation
        if ($appointment->status === 'annule') {
            $events[] = [
                'label' => 'Rendez-vous annulé',
                'date'  => $appointment->updated_at,
                'icon'  => '❌',
            ];
        }

        // 🩺 Dossier médical
        if ($appointment->medicalRecord) {
            $events[] = [
                'label' => 'Dossier médical créé',
                'date'  => $appointment->medicalRecord->created_at,
                'icon'  => '🩺',
            ];
        }

        return Inertia::render('Appointments/Timeline', [
            'appointment' => $appointment,
            'events'      => $events,
        ]);
    }
}
