<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * =====================
     * 👨‍⚕️ DASHBOARD MÉDECIN
     * =====================
     */
    public function medecin(Request $request)
    {
        $user = $request->user();

        $today = now()->toDateString();

        // =====================
        // 📊 STATS (OK)
        // =====================
        $stats = [
            'today' => $user->medecinAppointments()
                ->whereDate('appointment_at', $today)
                ->count(),

            'week' => $user->medecinAppointments()
                ->whereBetween('appointment_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ])
                ->count(),

            'cancelled' => $user->medecinAppointments()
                ->where('status', 'annule')
                ->count(),

            'revenue' => $user->medecinAppointments()
                ->whereHas('invoice')
                ->with('invoice')
                ->get()
                ->sum(fn($a) => $a->invoice?->amount ?? 0),
        ];

        // =====================
        // 📈 GRAPH WEEK (REQUÊTE PROPRE)
        // =====================
        $weekRaw = $user->medecinAppointments()
            ->selectRaw('DAYOFWEEK(appointment_at) as day, COUNT(*) as total')
            ->whereBetween('appointment_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $weekLabels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $weekData = [];

        for ($i = 1; $i <= 7; $i++) {
            $weekData[] = $weekRaw[$i] ?? 0;
        }

        // =====================
        // 📈 GRAPH MONTH (REQUÊTE PROPRE)
        // =====================
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $monthRaw = $user->medecinAppointments()
            ->selectRaw('DAY(appointment_at) as day, COUNT(*) as total')
            ->whereBetween('appointment_at', [$monthStart, $monthEnd])
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $monthLabels = [];
        $monthData = [];

        for ($d = 1; $d <= $monthEnd->day; $d++) {
            $monthLabels[] = (string) $d;
            $monthData[] = $monthRaw[$d] ?? 0;
        }

        // =====================
        // 📈 GRAPH YEAR (REQUÊTE PROPRE)
        // =====================
        $yearRaw = $user->medecinAppointments()
            ->selectRaw('MONTH(appointment_at) as month, COUNT(*) as total')
            ->whereYear('appointment_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $yearLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        $yearData = [];

        for ($m = 1; $m <= 12; $m++) {
            $yearData[] = $yearRaw[$m] ?? 0;
        }

        return Inertia::render('Medecin/Dashboard', [
            'stats' => $stats,
            'chart' => [
                'week' => [
                    'labels' => $weekLabels,
                    'data' => $weekData,
                ],
                'month' => [
                    'labels' => $monthLabels,
                    'data' => $monthData,
                ],
                'year' => [
                    'labels' => $yearLabels,
                    'data' => $yearData,
                ],
            ],
        ]);
    }


    /**
     * =====================
     * 🧑‍⚕️ DASHBOARD PATIENT
     * =====================
     */
    public function patient(Request $request)
    {
        $user = $request->user();

        // =====================
        // 📊 STATS
        // =====================
        $nextAppointment = $user->patientAppointments()
            ->where('appointment_at', '>', now())
            ->orderBy('appointment_at')
            ->first();

        $stats = [
            'nextAppointment' => $nextAppointment?->appointment_at,
            'upcoming' => $user->patientAppointments()
                ->where('appointment_at', '>', now())
                ->count(),

            'unpaidInvoices' => $user->invoices()
                ->where('status', 'unpaid')
                ->count(),
        ];

        // =====================
        // 📈 GRAPH WEEK
        // =====================
        $weekRaw = $user->patientAppointments()
            ->selectRaw('DAYOFWEEK(appointment_at) as day, COUNT(*) as total')
            ->whereBetween('appointment_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $weekLabels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $weekData = [];

        for ($i = 1; $i <= 7; $i++) {
            $weekData[] = $weekRaw[$i] ?? 0;
        }

        // =====================
        // 📈 GRAPH MONTH
        // =====================
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $monthRaw = $user->patientAppointments()
            ->selectRaw('DAY(appointment_at) as day, COUNT(*) as total')
            ->whereBetween('appointment_at', [$monthStart, $monthEnd])
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $monthLabels = [];
        $monthData = [];

        for ($d = 1; $d <= $monthEnd->day; $d++) {
            $monthLabels[] = (string) $d;
            $monthData[] = $monthRaw[$d] ?? 0;
        }

        // =====================
        // 📈 GRAPH YEAR
        // =====================
        $yearRaw = $user->patientAppointments()
            ->selectRaw('MONTH(appointment_at) as month, COUNT(*) as total')
            ->whereYear('appointment_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $yearLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        $yearData = [];

        for ($m = 1; $m <= 12; $m++) {
            $yearData[] = $yearRaw[$m] ?? 0;
        }

        return Inertia::render('Patient/Dashboard', [
            'stats' => $stats,
            'chart' => [
                'week' => [
                    'labels' => $weekLabels,
                    'data' => $weekData,
                ],
                'month' => [
                    'labels' => $monthLabels,
                    'data' => $monthData,
                ],
                'year' => [
                    'labels' => $yearLabels,
                    'data' => $yearData,
                ],
            ],
        ]);
    }
}
