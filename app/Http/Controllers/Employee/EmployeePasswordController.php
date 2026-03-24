<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Contrôleur pour la gestion du mot de passe des employés.
 * 
 * Permet aux employés de modifier leur mot de passe de manière sécurisée
 * en vérifiant d'abord le mot de passe actuel.
 */
class EmployeePasswordController extends Controller
{
    /**
     * Affiche le formulaire de changement de mot de passe.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $employee = Auth::guard('employees')->user();
        return view('employee.password.edit', compact('employee'));
    }

    /**
     * Met à jour le mot de passe de l'employé.
     * 
     * Exigences du nouveau mot de passe :
     * - Minimum 8 caractères
     * - Mélange de majuscules et minuscules
     * - Au moins un chiffre
     *
     * @param  \Illuminate\Http\Request  $request  La requête contenant l'ancien et le nouveau mot de passe
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $employee = Auth::guard('employees')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        // Vérification du mot de passe actuel avant modification
        if (!Hash::check($request->current_password, $employee->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        // Mise à jour avec hashage sécurisé du nouveau mot de passe
        $employee->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }
}
