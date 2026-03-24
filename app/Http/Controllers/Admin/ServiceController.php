<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Contrôleur de gestion des services pour le panneau d'administration.
 * 
 * Ce contrôleur gère l'ensemble du catalogue de services proposés par le salon :
 * - Opérations CRUD (création, lecture, modification, suppression)
 * - Gestion des promotions et tarifs spéciaux
 * - Upload et gestion des photos de services
 * - Association des employés qualifiés pour chaque service
 * 
 * Les services peuvent être filtrés par genre (homme, femme, enfant, mixte)
 * et catégorisés (coiffure, manucure, soins, etc.).
 * 
 * @package App\Http\Controllers\Admin
 * @author Système de gestion Salon de Beauté
 */
class ServiceController extends Controller
{
    // ==========================================================================
    // SECTION CRUD - OPÉRATIONS DE BASE
    // ==========================================================================

    /**
     * Affiche la liste paginée de tous les services.
     * 
     * Récupère les services avec les employés qui leur sont assignés.
     * La pagination est fixée à 10 éléments par page.
     *
     * @return \Illuminate\View\View Vue de la liste des services
     */
    public function index()
    {
        // Chargement eager de la relation 'employees' pour éviter le N+1
        $services = Service::with('employees')->paginate(10);
        
        return view('admin.services.index', compact('services'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau service.
     * 
     * Prépare la liste des employés actifs qui peuvent être
     * assignés au nouveau service.
     *
     * @return \Illuminate\View\View Vue du formulaire de création
     */
    public function create()
    {
        // Récupération des employés actifs triés par nom
        $employees = Employee::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('admin.services.create', compact('employees'));
    }

    /**
     * Enregistre un nouveau service dans la base de données.
     * 
     * Cette méthode gère :
     * - Validation complète des données du formulaire
     * - Upload et stockage des photos (max 5 photos, 2Mo chacune)
     * - Création du service avec ses tarifs et promotions
     * - Association des employés qualifiés
     * 
     * Champs de promotion :
     * - promotion_price : Prix réduit pendant la promotion
     * - promotion_label : Libellé affiché (ex: "-20%", "Offre spéciale")
     * - promotion_start/end : Période de validité de la promotion
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les données du formulaire
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function store(Request $request)
    {
        // ======================================================================
        // VALIDATION DES DONNÉES
        // Règles strictes pour garantir l'intégrité des données
        // ======================================================================
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'gender' => 'required|in:homme,femme,enfant,mixte',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1', // Durée en minutes
            'promotion_price' => 'nullable|numeric|min:0|lt:price', // Doit être < prix normal
            'promotion_label' => 'nullable|string|max:255',
            'promotion_start' => 'nullable|date',
            'promotion_end' => 'nullable|date|after_or_equal:promotion_start',
            'active' => 'boolean',
            'employees' => 'nullable|array',
            'employees.*' => 'exists:employees,id',
            'photos' => 'nullable|array|max:5', // Maximum 5 photos
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // 2Mo max par photo
        ]);

        // Extraction des IDs des employés avant la création
        $employeeIds = $validated['employees'] ?? [];
        unset($validated['employees']);

        try {
            // ======================================================================
            // GESTION DES PHOTOS
            // Stockage dans le dossier 'services' du disque 'public'
            // ======================================================================
            $photoPaths = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    // Stockage avec nom unique généré automatiquement
                    $path = $photo->store('services', 'public');
                    $photoPaths[] = $path;
                }
            }
            $validated['photos'] = $photoPaths;

            // ======================================================================
            // CRÉATION DU SERVICE ET ASSOCIATION DES EMPLOYÉS
            // ======================================================================
            $service = Service::create($validated);
            $service->employees()->sync($employeeIds);

            return redirect()->route('admin.services.index')
                ->with('success', 'Service ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire de modification d'un service existant.
     * 
     * Charge le service avec ses employés actuels pour pré-remplir
     * le formulaire d'édition.
     *
     * @param  \App\Models\Service  $service Instance du service à modifier
     * @return \Illuminate\View\View Vue du formulaire d'édition
     */
    public function edit(Service $service)
    {
        $employees = Employee::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        // Chargement des employés actuellement assignés
        $service->load('employees');
        
        return view('admin.services.edit', compact('service', 'employees'));
    }

    /**
     * Met à jour un service existant dans la base de données.
     * 
     * Cette méthode gère également :
     * - La suppression des photos marquées pour suppression
     * - L'ajout de nouvelles photos tout en conservant les existantes
     * - La mise à jour des employés assignés
     * 
     * Les photos sont stockées sur le disque 'public' et accessibles
     * via le lien symbolique storage -> public/storage.
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les nouvelles données
     * @param  \App\Models\Service  $service Instance du service à mettre à jour
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function update(Request $request, Service $service)
    {
        // Validation identique à la création avec champ supplémentaire delete_photos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'gender' => 'required|in:homme,femme,enfant,mixte',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'promotion_price' => 'nullable|numeric|min:0|lt:price',
            'promotion_label' => 'nullable|string|max:255',
            'promotion_start' => 'nullable|date',
            'promotion_end' => 'nullable|date|after_or_equal:promotion_start',
            'active' => 'boolean',
            'employees' => 'nullable|array',
            'employees.*' => 'exists:employees,id',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_photos' => 'nullable|array', // Indices des photos à supprimer
        ]);

        // Extraction des IDs des employés
        $employeeIds = $validated['employees'] ?? [];
        unset($validated['employees']);

        try {
            // ======================================================================
            // GESTION DE LA SUPPRESSION DES PHOTOS EXISTANTES
            // Les photos sont supprimées du disque et retirées du tableau
            // ======================================================================
            $currentPhotos = $service->photos ?? [];
            $deleteIndices = $request->input('delete_photos', []);
            
            if (!empty($deleteIndices)) {
                foreach ($deleteIndices as $index) {
                    if (isset($currentPhotos[$index])) {
                        // Suppression physique du fichier
                        Storage::disk('public')->delete($currentPhotos[$index]);
                        unset($currentPhotos[$index]);
                    }
                }
                // Réindexation du tableau après suppression
                $currentPhotos = array_values($currentPhotos);
            }

            // ======================================================================
            // AJOUT DES NOUVELLES PHOTOS
            // Les nouvelles photos sont ajoutées à la suite des existantes
            // ======================================================================
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('services', 'public');
                    $currentPhotos[] = $path;
                }
            }
            $validated['photos'] = $currentPhotos;
            unset($validated['delete_photos']);

            // ======================================================================
            // MISE À JOUR DU SERVICE ET DES ASSOCIATIONS
            // ======================================================================
            $service->update($validated);
            $service->employees()->sync($employeeIds);

            return redirect()->route('admin.services.index')
                ->with('success', 'Service mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Supprime un service de la base de données.
     * 
     * Cette méthode effectue une suppression complète :
     * - Détache d'abord les employés pour éviter les erreurs de contrainte
     * - Puis supprime le service
     * 
     * Note : Les photos ne sont pas automatiquement supprimées du disque.
     * Une tâche de nettoyage périodique peut être mise en place pour
     * supprimer les fichiers orphelins.
     *
     * @param  \App\Models\Service  $service Instance du service à supprimer
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function destroy(Service $service)
    {
        try {
            // Détachement des employés avant suppression (relation many-to-many)
            $service->employees()->detach();
            $service->delete();

            return redirect()->route('admin.services.index')
                ->with('success', 'Service supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}
