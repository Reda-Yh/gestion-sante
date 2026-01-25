<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Affiche les statistiques (Dashboard)
    public function dashboard(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total' => User::count(),
                'patients' => User::where('role', 'patient')->count(),
                'medecins' => User::where('role', 'medecin')->count(),
                'admins' => User::where('role', 'admin')->count(),
                'banned' => User::where('banned', true)->count(),
                'active' => User::where('banned', false)->count(),
            ]
        ]);
    }
    
    public function index(): Response
    {
        // On récupère les utilisateurs triés par ID
        return Inertia::render('Admin/Users', [
            'users' => User::orderBy('id', 'asc')->get()
        ]);
    }

    public function updateRole(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'role' => 'required|in:patient,medecin,admin'
        ]);

        $user = User::findOrFail($id);

        // Correction Intelephense : utiliser Auth::id()
        if ($user->id === Auth::id() && $request->role !== 'admin') {
            return redirect()->back()->with('error', 'Vous ne pouvez pas changer votre propre rôle.');
        }

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'Rôle mis à jour.');
    }

    /**
     * Basculer l'état de bannissement (Ban/Unban)
     */
    public function toggleBan($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Action impossible sur votre propre compte.');
        }

        // Inverse la valeur actuelle (si true devient false, et vice versa)
        $user->banned = !$user->banned;
        $user->save();

        $status = $user->banned ? 'banni' : 'débanni';
        return redirect()->back()->with('success', "Utilisateur $status avec succès.");
    }
}
