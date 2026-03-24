<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

/**
 * Contrôleur pour la gestion du profil des employés.
 * 
 * Permet aux employés de consulter et modifier leurs informations
 * personnelles ainsi que leur mot de passe.
 */
class EmployeeProfileController extends Controller
{
    /**
     * Affiche la page de profil de l'employé connecté.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $employee = Auth::guard('employees')->user();
        return view('employee.profile.index', compact('employee'));
    }

    /**
     * Met à jour les informations du profil de l'employé.
     * 
     * Champs modifiables : nom, email et téléphone.
     * L'email doit être unique parmi tous les employés.
     *
     * @param  \Illuminate\Http\Request  $request  La requête contenant les nouvelles informations
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $employee = Auth::guard('employees')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Met à jour le mot de passe de l'employé depuis la page de profil.
     * 
     * Nécessite la saisie du mot de passe actuel pour validation.
     * Le nouveau mot de passe doit contenir au moins 8 caractères.
     *
     * @param  \Illuminate\Http\Request  $request  La requête contenant l'ancien et le nouveau mot de passe
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $employee = Auth::guard('employees')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Vérification du mot de passe actuel
        if (!Hash::check($request->current_password, $employee->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        // Hashage et enregistrement du nouveau mot de passe
        $employee->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Mot de passe modifié avec succès.');
    }

    /**
     * Met à jour la photo de profil de l'employé.
     * 
     * Accepte les formats JPEG, PNG, JPG et GIF (max 2 Mo).
     * Supprime l'ancienne photo du stockage avant d'enregistrer la nouvelle.
     *
     * @param  \Illuminate\Http\Request  $request  La requête contenant le fichier photo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePhoto(Request $request)
    {
        $employee = Auth::guard('employees')->user();

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Suppression de l'ancienne photo si elle existe sur le disque
        if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
            Storage::disk('public')->delete($employee->photo);
        }

        // Stockage de la nouvelle photo dans le dossier public/photos/employees
        $path = $request->file('photo')->store('photos/employees', 'public');
        $employee->update(['photo' => $path]);

        return redirect()->route('employee.profile')->with('success', 'Photo mise à jour.');
    }

    /**
     * Supprime la photo de profil de l'employé.
     * 
     * Efface le fichier du stockage et remet le champ photo à null.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePhoto()
    {
        $employee = Auth::guard('employees')->user();

        // Suppression du fichier physique sur le disque si existant
        if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
            Storage::disk('public')->delete($employee->photo);
        }

        // Réinitialisation du champ photo dans la base de données
        $employee->update(['photo' => null]);

        return redirect()->route('employee.profile')->with('success', 'Photo supprimée.');
    }
}
