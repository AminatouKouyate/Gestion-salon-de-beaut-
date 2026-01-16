<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'price' => 'required|integer|min:0',
            'duration' => 'required|integer|min:1',
            'active' => 'boolean',
        ]);

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service ajouté avec succès.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'price' => 'required|integer|min:0',
            'duration' => 'required|integer|min:1',
            'active' => 'boolean',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service mis à jour avec succès.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service supprimé avec succès.');
    }
}
