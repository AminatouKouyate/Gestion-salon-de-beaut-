<?php

namespace App\Http\Controllers\Client;

use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::active();

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
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

        $services = $query->paginate(12)->withQueryString();
        $categories = Service::distinct()->pluck('category');

        return view('Clients.service.index', compact('services', 'categories', 'request'));
    }

    public function publicIndex()
    {
        $services = Service::active()->get()->groupBy('category');
        return view('Clients.service.public', compact('services'));
    }

    public function show(Service $service)
    {
        $service->load(['employees' => function ($q) {
            $q->where('is_active', true);
        }]);
        return view('Clients.service.show', compact('service'));
    }
}
