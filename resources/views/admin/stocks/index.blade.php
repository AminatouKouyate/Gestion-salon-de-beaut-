@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        {{-- Titre --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestion des stocks</h1>

            <a href="{{ route('admin.stocks.create') }}" class="btn btn-primary">
                + Ajouter un produit
            </a>
        </div>

        {{-- Alertes stock faible --}}
        @if($lowStocks->count() > 0)
            <div class="alert alert-warning">
                <strong>Attention :</strong> certains produits ont un stock faible.
            </div>
        @endif

        {{-- Tableau des stocks --}}
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Produit</th>
                            <th>Catégorie</th>
                            <th>Quantité</th>
                            <th>Seuil</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                            <tr>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->category }}</td>
                                <td>{{ $stock->quantity }}</td>
                                <td>{{ $stock->alert_threshold }}</td>
                                <td>
                                    @if($stock->quantity <= 0)
                                        <span class="badge bg-danger">Rupture</span>
                                    @elseif($stock->quantity <= $stock->alert_threshold)
                                        <span class="badge bg-warning text-dark">Stock faible</span>
                                    @else
                                        <span class="badge bg-success">Disponible</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.stocks.edit', $stock) }}"
                                       class="btn btn-sm btn-warning">
                                        Modifier
                                    </a>

                                    <form action="{{ route('admin.stocks.destroy', $stock) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Supprimer ce produit ?')">
                                            Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Aucun produit en stock
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
