@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <h1 class="mb-4">Ajouter un produit</h1>

        <form action="{{ route('admin.stocks.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nom du produit</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Catégorie</label>
                <input type="text" name="category" class="form-control">
            </div>

            <div class="mb-3">
                <label>Quantité</label>
                <input type="number" name="quantity" class="form-control" min="0" required>
            </div>

            <div class="mb-3">
                <label>Seuil d’alerte</label>
                <input type="number" name="alert_threshold" class="form-control" min="0" required>
            </div>

            <button class="btn btn-success">Enregistrer</button>
            <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">Annuler</a>
        </form>

    </div>
</div>
@endsection
