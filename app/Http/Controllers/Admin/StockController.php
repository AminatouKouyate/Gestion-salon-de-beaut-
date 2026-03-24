<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Employee;
use Illuminate\Http\Request;

/**
 * Contrôleur de gestion des stocks de produits pour le panneau d'administration.
 * 
 * Ce contrôleur gère l'inventaire des produits du salon :
 * - Opérations CRUD complètes (création, lecture, modification, suppression)
 * - Suivi des quantités en stock et des seuils d'alerte
 * - Catégorisation des produits
 * - Détection automatique des produits en stock faible
 * 
 * Note de compatibilité : le champ 'product_name' est maintenu en doublon
 * du champ 'name' pour assurer la rétrocompatibilité avec les anciennes données.
 * Les requêtes utilisent COALESCE pour gérer les deux noms de colonnes.
 * 
 * @package App\Http\Controllers\Admin
 */
class StockController extends Controller
{
    /**
     * Affiche la liste de tous les produits en stock avec les alertes de stock faible.
     * 
     * Récupère deux collections :
     * - La liste complète des produits triés par nom
     * - Les produits dont la quantité est inférieure ou égale au seuil d'alerte
     *
     * @return \Illuminate\View\View Vue de la liste des produits en stock
     */
    public function index()
    {
        // Récupère tous les produits triés par nom
        // COALESCE gère la compatibilité entre l'ancien champ 'product_name' et le nouveau 'name'
        $stocks = Stock::orderByRaw('COALESCE(name, product_name)')->get();

        // Identifie les produits en stock faible (quantité <= seuil d'alerte)
        // COALESCE gère la compatibilité entre 'alert_threshold' et l'ancien 'alert_quantity'
        $lowStocks = Stock::whereRaw('quantity <= COALESCE(alert_threshold, alert_quantity, 0)')->get();

        return view('admin.stocks.index', compact(
            'stocks',
            'lowStocks'
        ));
    }

    /**
     * Affiche le formulaire de création d'un nouveau produit en stock.
     *
     * @return \Illuminate\View\View Vue du formulaire de création de produit
     */
    public function create()
    {
        return view('admin.stocks.create');
    }

    /**
     * Enregistre un nouveau produit dans le stock.
     * 
     * Valide les données puis crée le produit en base de données.
     * Le champ 'product_name' est automatiquement renseigné avec la valeur
     * du champ 'name' pour maintenir la compatibilité avec les anciennes données.
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant 'name', 'category', 'quantity' et 'alert_threshold'
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function store(Request $request)
    {
        // Validation des données : nom unique, quantité et seuil d'alerte obligatoires
        $request->validate([
            'name' => 'required|string|max:255|unique:stocks,name',
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'alert_threshold' => 'required|integer|min:0',
        ]);

        try {
            // Création du produit avec duplication du nom dans 'product_name' (rétrocompatibilité)
            Stock::create(array_merge($request->all(), ['product_name' => $request->name]));

            return redirect()->route('admin.stocks.index')->with('success', 'Produit ajouté au stock avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire de modification d'un produit en stock.
     *
     * @param  \App\Models\Stock  $stock Instance du produit à modifier (injection automatique par Laravel)
     * @return \Illuminate\View\View Vue du formulaire d'édition pré-rempli
     */
    public function edit(Stock $stock)
    {
        return view('admin.stocks.edit', compact('stock'));
    }

    /**
     * Met à jour les informations d'un produit en stock.
     * 
     * La validation exclut le produit courant de la vérification d'unicité
     * du nom pour permettre de conserver le même nom lors de la modification.
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les nouvelles données du produit
     * @param  \App\Models\Stock  $stock Instance du produit à mettre à jour
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function update(Request $request, Stock $stock)
    {
        // Validation avec exclusion du produit courant pour la règle d'unicité du nom
        $request->validate([
            'name' => 'required|string|max:255|unique:stocks,name,' . $stock->id,
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'alert_threshold' => 'required|integer|min:0',
        ]);

        try {
            // Mise à jour du produit avec synchronisation de 'product_name' (rétrocompatibilité)
            $stock->update(array_merge($request->all(), ['product_name' => $request->name]));

            return redirect()->route('admin.stocks.index')->with('success', 'Stock mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Supprime un produit du stock.
     * 
     * Effectue une suppression définitive du produit en base de données.
     *
     * @param  \App\Models\Stock  $stock Instance du produit à supprimer
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function destroy(Stock $stock)
    {
        try {
            $stock->delete();

            return redirect()->route('admin.stocks.index')->with('success', 'Produit supprimé du stock avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}
