<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;


use App\Models\Employee;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tous les produits
        $stocks = Stock::orderBy('name')->get();

        // Produits à stock faible ou critique
        $lowStocks = Stock::whereColumn('quantity', '<=', 'alert_threshold')->get();

        return view('admin.stocks.index', compact(
            'stocks',
            'lowStocks'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.stocks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:stocks,name',
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'alert_threshold' => 'required|integer|min:0',
        ]);

        Stock::create($request->all());

        return redirect()->route('admin.stocks.index')->with('success', 'Produit ajouté au stock avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        return view('admin.stocks.edit', compact('stock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'product_name' => 'required|string|max:255|unique:stocks,product_name,' . $stock->id,
            'quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
        ]);

        $stock->update($request->all());

        return redirect()->route('admin.stocks.index')->with('success', 'Stock mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('admin.stocks.index')->with('success', 'Produit supprimé du stock avec succès.');
    }
}
