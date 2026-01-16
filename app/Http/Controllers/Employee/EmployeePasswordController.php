<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EmployeePasswordController extends Controller
{
    /**
     * Affiche le formulaire de changement de mot de passe.
     */
    public function edit()
    {
        $employee = Auth::guard('employees')->user();
        return view('employee.password.edit', compact('employee'));
    }

    /**
     * Met à jour le mot de passe de l'employé.
     */
    public function update(Request $request)
    {
        $employee = Auth::guard('employees')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $employee->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        // Mettre à jour le mot de passe
        $employee->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }
}
