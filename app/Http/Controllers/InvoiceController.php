<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    /**
     * Liste des factures (patient)
     */
    public function index(Request $request)
    {
        $invoices = $request->user()
            ->invoices()
            ->with('appointment')
            ->latest()
            ->get();

        return Inertia::render('Patient/Invoices/Index', [
            'invoices' => $invoices,
        ]);
    }

    /**
     * Paiement simulé
     */
    public function pay(Invoice $invoice, Request $request)
    {
        if ($invoice->patient_id !== $request->user()->id) {
            abort(403);
        }

        $invoice->update([
            'status' => 'paid',
        ]);

        return back()->with('success', 'Paiement effectué avec succès (simulé)');
    }

    /**
     * Télécharger la facture PDF
     */
    public function pdf(Invoice $invoice, Request $request)
    {
        if ($invoice->patient_id !== $request->user()->id) {
            abort(403);
        }

        $invoice->load(['patient', 'appointment']);

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
        ]);

        return $pdf->download('facture-' . $invoice->id . '.pdf');
    }
}
