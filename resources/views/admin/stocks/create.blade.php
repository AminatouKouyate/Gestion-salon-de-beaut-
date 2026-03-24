@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-cube"></i></div>
                <div>
                    <h2 class="beauty-page-title">Ajouter un produit </h2>
                    <p class="beauty-page-subtitle">Ajouter un nouveau produit en stock</p>
                </div>
            </div>
            <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-2"></i>Retour à la liste
            </a>
        </div>

        @include('partials.success')
        @include('partials.error')

        <div class="beauty-card">
        <div class="beauty-card-body">
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

    </div>
</div>
@endsection
