@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Modifier un produit en stock</h1>
    <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">Retour à la liste</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.stocks.update', $stock->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nom du produit</label>
                <input type="text" name="product_name" class="form-control" value="{{ $stock->product_name }}" required>
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
