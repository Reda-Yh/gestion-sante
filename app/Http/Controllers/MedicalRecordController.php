<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicalRecordController extends Controller
{
    /**
     * Formulaire de création du dossier médical (médecin)
     */
    public function create(Appointment $appointment, Request $request)
    {
        if ($appointment->medecin_id !== $request->user()->id) {
            abort(403);
        }

        if ($appointment->medicalRecord) {
            abort(403, 'Dossier médical déjà créé');
        }

        return Inertia::render('Medecin/MedicalRecords/Create', [
            'appointment' => $appointment->load('patient'),
        ]);
    }

    /**
     * Enregistrer le dossier médical + facture + signature
     */
    public function store(Appointment $appointment, Request $request)
    {
        if ($appointment->medecin_id !== $request->user()->id) {
            abort(403);
        }

        if ($appointment->medicalRecord) {
            abort(403);
        }

        $data = $request->validate([
            'diagnostic' => 'required|string',
            'traitement' => 'nullable|string',
            'notes'      => 'nullable|string',
            'amount'     => 'required|numeric|min:0',
            'signature'  => 'nullable|string', // ✍️ signature base64
        ]);

        // 🩺 Création du dossier médical
        $medicalRecord = MedicalRecord::create([
            'patient_id'     => $appointment->patient_id,
            'medecin_id'     => $appointment->medecin_id,
            'appointment_id' => $appointment->id,
            'diagnostic'     => $data['diagnostic'],
            'traitement'     => $data['traitement'] ?? null,
            'notes'          => $data['notes'] ?? null,
            'signature'      => $data['signature'] ?? null,
        ]);

        // 💳 Création de la facture
        Invoice::create([
            'patient_id'     => $appointment->patient_id,
            'appointment_id' => $appointment->id,
            'amount'         => $data['amount'],
            'status'         => 'unpaid',
        ]);

        return redirect()
            ->route('medical-records.show', $medicalRecord->id)
            ->with('success', 'Dossier médical créé, signé et facturé');
    }

    /**
     * Affichage dossier médical
     */
    public function show(MedicalRecord $medicalRecord, Request $request)
    {
        $user = $request->user();

        if (
            $medicalRecord->patient_id !== $user->id &&
            $medicalRecord->medecin_id !== $user->id
        ) {
            abort(403);
        }

        return Inertia::render('MedicalRecords/Show', [
            'medicalRecord' => $medicalRecord->load([
                'patient:id,name',
                'medecin:id,name',
                'appointment:id,appointment_at',
            ]),
            'role' => $user->role,
        ]);
    }

    /**
     * PDF du dossier médical
     */
    public function pdf(MedicalRecord $medicalRecord, Request $request)
    {
        $user = $request->user();

        if (
            $medicalRecord->patient_id !== $user->id &&
            $medicalRecord->medecin_id !== $user->id
        ) {
            abort(403);
        }

        $medicalRecord->load(['patient', 'medecin', 'appointment']);

        $pdf = Pdf::loadView('pdf.medical-record', [
            'record' => $medicalRecord,
        ]);

        return $pdf->download(
            'dossier-medical-' . $medicalRecord->id . '.pdf'
        );
    }
}
