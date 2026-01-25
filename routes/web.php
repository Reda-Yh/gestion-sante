<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Page publique
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| Dashboard (redirection selon rôle)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    $user = $request->user();

    return match ($user->role) {
        'admin'   => redirect()->route('admin.dashboard'),
        'medecin' => redirect()->route('medecin.dashboard'),
        default   => redirect()->route('patient.dashboard'),
    };
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Routes protégées
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |-----------------------------------------
    | Profil (commun)
    |-----------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |-----------------------------------------
    | 🔔 Notifications
    |-----------------------------------------
    */
    Route::post('/notifications/{notification}/read', function ($notificationId) {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $notificationId)
            ->firstOrFail();

        $notification->markAsRead();

        return back();
    })->name('notifications.read');

    /*
    |-----------------------------------------
    | 🕒 Timeline
    |-----------------------------------------
    */
    Route::get(
        '/appointments/{appointment}/timeline',
        [AppointmentController::class, 'timeline']
    )->name('appointments.timeline');

    /*
    |-----------------------------------------
    | Dossier médical
    |-----------------------------------------
    */
    Route::get(
        '/medical-records/{medicalRecord}',
        [MedicalRecordController::class, 'show']
    )->name('medical-records.show');

    Route::get(
        '/medical-records/{medicalRecord}/pdf',
        [MedicalRecordController::class, 'pdf']
    )->name('medical-records.pdf');

    /*
    |-----------------------------------------
    | 👨‍⚕️ Médecin
    |-----------------------------------------
    */
    Route::middleware(['role:medecin'])
        ->prefix('medecin')
        ->name('medecin.')
        ->group(function () {

            // ✅ CORRIGÉ (PAS de /medecin en plus)
            Route::get('/dashboard', [DashboardController::class, 'medecin'])
                ->name('dashboard');

            Route::get('/appointments', [AppointmentController::class, 'indexMedecin'])
                ->name('appointments.index');

            Route::get('/invitations', [AppointmentController::class, 'invitations'])
                ->name('appointments.invitations');

            Route::get('/calendar', [AppointmentController::class, 'calendar'])
                ->name('calendar');

            Route::patch(
                '/appointments/{appointment}/update-time',
                [AppointmentController::class, 'updateTime']
            )->name('appointments.update-time');

            Route::patch(
                '/appointments/{appointment}/confirm',
                [AppointmentController::class, 'confirm']
            )->name('appointments.confirm');

            Route::patch(
                '/appointments/{appointment}/cancel',
                [AppointmentController::class, 'cancel']
            )->name('appointments.cancel');

            Route::delete(
                '/appointments/{appointment}',
                [AppointmentController::class, 'destroy']
            )->name('appointments.destroy');

            // Dossiers médicaux
            Route::get(
                '/appointments/{appointment}/medical-record/create',
                [MedicalRecordController::class, 'create']
            )->name('medical-records.create');

            Route::post(
                '/appointments/{appointment}/medical-record',
                [MedicalRecordController::class, 'store']
            )->name('medical-records.store');
        });

    /*
    |-----------------------------------------
    | 🧑‍⚕️ Patient
    |-----------------------------------------
    */
    Route::middleware(['role:patient'])
        ->prefix('patient')
        ->name('patient.')
        ->group(function () {

            // ✅ CORRIGÉ (PAS de /patient en plus)
            Route::get('/dashboard', [DashboardController::class, 'patient'])
                ->name('dashboard');

            Route::get('/calendar', [AppointmentController::class, 'calendarPatient'])
                ->name('calendar');

            Route::get('/appointments/create', [AppointmentController::class, 'create'])
                ->name('appointments.create');

            Route::post('/appointments', [AppointmentController::class, 'store'])
                ->name('appointments.store');

            Route::get('/appointments', [AppointmentController::class, 'indexPatient'])
                ->name('appointments.index');

            Route::get('/invoices', [InvoiceController::class, 'index'])
                ->name('invoices.index');

            Route::patch('/invoices/{invoice}/pay', [InvoiceController::class, 'pay'])
                ->name('invoices.pay');

            Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])
                ->name('invoices.pdf');
        });

    /*
|-----------------------------------------
| 👑 Admin
|-----------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Modification ici : on lie la route au contrôleur pour récupérer les stats
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/users', [AdminController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/role', [AdminController::class, 'updateRole'])->name('users.updateRole');

    // On utilise toggle pour gérer Ban et Unban avec un seul bouton
    Route::post('/users/{id}/toggle-ban', [AdminController::class, 'toggleBan'])->name('users.toggleBan');
});

});

require __DIR__ . '/auth.php';
