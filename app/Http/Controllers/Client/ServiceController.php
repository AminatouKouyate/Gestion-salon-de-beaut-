<?php

namespace App\Http\Controllers\Client;

use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Contrôleur pour l'affichage des services côté client.
 * 
 * Permet aux clients de consulter le catalogue des services disponibles
 * avec filtrage par catégorie, prix et durée.
 */
class ServiceController extends Controller
{
    /**
     * Affiche la liste des services avec options de filtrage.
     *
     * Permet de filtrer par catégorie, prix minimum/maximum et durée.
     *
     * @param Request $request Requête contenant les paramètres de filtrage
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Service::active();

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filtrage par genre (homme, femme, enfant)
        if ($request->filled('gender')) {
            $query->where(function($q) use ($request) {
                $q->where('gender', $request->gender)
                  ->orWhere('gender', 'mixte');
            });
        }

        // Filtrage par prix
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtrage par durée
        if ($request->filled('min_duration')) {
            $query->where('duration', '>=', $request->min_duration);
        }
        if ($request->filled('max_duration')) {
            $query->where('duration', '<=', $request->max_duration);
        }

        $services = $query->orderBy('category')->orderBy('name')->paginate(12)->withQueryString();
        $categories = Service::active()->distinct()->pluck('category');

        return view('clients.service.index', compact('services', 'categories', 'request'));
    }

    /**
     * Affiche la page publique des services groupés par catégorie.
     *
     * Page accessible sans authentification.
     *
     * @return \Illuminate\View\View
     */
    public function publicIndex()
    {
        $services = Service::active()->get()->groupBy('category');
        return view('clients.service.public', compact('services'));
    }

    /**
     * Affiche les détails d'un service spécifique.
     *
     * Charge également les employés actifs proposant ce service.
     *
     * @param Service $service Le service à afficher
     * @return \Illuminate\View\View
     */
    public function show(Service $service)
    {
        $service->load(['employees' => function ($q) {
            $q->where('is_active', true);
        }]);
        return view('clients.service.show', compact('service'));
    }
}
