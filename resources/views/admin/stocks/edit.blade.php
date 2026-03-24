@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
<div class="beauty-page-header">
    <div class="beauty-page-header-left">
        <div class="beauty-page-icon"><i class="fa fa-cubes"></i></div>
        <div>
            <h2 class="beauty-page-title">Modifier le produit </h2>
            <p class="beauty-page-subtitle">Mettre à jour les informations</p>
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
        <form action="{{ route('admin.stocks.update', $stock->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nom du produit</label>
                <input type="text" name="name" class="form-control" value="{{ $stock->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Quantité</label>
                <input type="number" name="quantity" class="form-control" value="{{ $stock->quantity }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Seuil d'alerte</label>
                <input type="number" name="alert_threshold" class="form-control" value="{{ $stock->alert_threshold }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
    </div>
</div>
@endsection
