@extends('admin.layouts.master')

@section('content')
<h2>Modifier un produit en stock</h2>

<form action="{{ route('admin.stocks.update', $stock->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Nom du produit</label>
        <input type="text" name="product_name" class="form-control" value="{{ $stock->product_name }}" required>
    </div>
    <div class="mb-3">
        <label>Quantité</label>
        <input type="number" name="quantity" class="form-control" value="{{ $stock->quantity }}" required>
    </div>
    <div class="mb-3">
        <label>Seuil d'alerte</label>
        <input type="number" name="alert_threshold" class="form-control" value="{{ $stock->alert_threshold }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>
@endsection
