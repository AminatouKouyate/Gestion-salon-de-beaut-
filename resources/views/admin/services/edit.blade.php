@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
<div class="beauty-page-header">
    <div class="beauty-page-header-left">
        <div class="beauty-page-icon"><i class="fa fa-scissors"></i></div>
        <div>
            <h2 class="beauty-page-title">Modifier le service </h2>
            <p class="beauty-page-subtitle">Mettre à jour les informations</p>
        </div>
    </div>
    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
        <i class="fa fa-arrow-left mr-2"></i>Retour à la liste
    </a>
</div>

@include('partials.success')
@include('partials.error')

<div class="beauty-card">
    <div class="beauty-card-body">
        <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nom du service</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $service->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $service->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label">Catégorie</label>
                    <select name="category" id="category" class="form-control @error('category') is-invalid @enderror">
                        <option value="">-- Sélectionner --</option>
                        <option value="coiffure" @if(old('category', $service->category) == 'coiffure') selected @endif>Coiffure</option>
                        <option value="maquillage" @if(old('category', $service->category) == 'maquillage') selected @endif>Maquillage</option>
                        <option value="soin" @if(old('category', $service->category) == 'soin') selected @endif>Soin</option>
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Public ciblé</label>
                    <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                        <option value="mixte" @if(old('gender', $service->gender) == 'mixte') selected @endif>Mixte (Tous)</option>
                        <option value="homme" @if(old('gender', $service->gender) == 'homme') selected @endif>Homme</option>
                        <option value="femme" @if(old('gender', $service->gender) == 'femme') selected @endif>Femme</option>
                        <option value="enfant" @if(old('gender', $service->gender) == 'enfant') selected @endif>Enfant</option>
                    </select>
                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Prix (FCFA)</label>
                    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $service->price) }}" required step="1" min="0">
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="duration" class="form-label">Durée (minutes)</label>
                    <input type="number" name="duration" id="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration', $service->duration) }}" required min="1">
                    @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr class="my-4">
            <h5 class="mb-3"><i class="fa fa-tag text-danger mr-2"></i>Promotion</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="promotion_price" class="form-label">Prix promotionnel (FCFA)</label>
                    <input type="number" name="promotion_price" id="promotion_price"
                           class="form-control @error('promotion_price') is-invalid @enderror"
                           value="{{ old('promotion_price', $service->promotion_price) }}"
                           step="1" min="0" placeholder="Laisser vide si pas de promotion">
                    @error('promotion_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="promotion_label" class="form-label">Libellé de la promotion</label>
                    <input type="text" name="promotion_label" id="promotion_label"
                           class="form-control @error('promotion_label') is-invalid @enderror"
                           value="{{ old('promotion_label', $service->promotion_label) }}"
                           placeholder="Ex: Soldes d'été, -20%, Offre spéciale...">
                    @error('promotion_label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="promotion_start" class="form-label">Date de début</label>
                    <input type="date" name="promotion_start" id="promotion_start"
                           class="form-control @error('promotion_start') is-invalid @enderror"
                           value="{{ old('promotion_start', $service->promotion_start ? $service->promotion_start->format('Y-m-d') : '') }}">
                    @error('promotion_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="promotion_end" class="form-label">Date de fin</label>
                    <input type="date" name="promotion_end" id="promotion_end"
                           class="form-control @error('promotion_end') is-invalid @enderror"
                           value="{{ old('promotion_end', $service->promotion_end ? $service->promotion_end->format('Y-m-d') : '') }}">
                    @error('promotion_end')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            @if($service->hasActivePromotion())
            <div class="alert alert-success">
                <i class="fa fa-check-circle mr-2"></i>
                <strong>Promotion active !</strong>
                Réduction de {{ $service->getDiscountPercentage() }}%
                ({{ number_format($service->price, 0, ',', ' ') }} → {{ number_format($service->promotion_price, 0, ',', ' ') }} FCFA)
            </div>
            @endif

            <hr class="my-4">

            <div class="mb-3">
                <label for="photos" class="form-label">Photos du service</label>
                @if($service->photos && count($service->photos) > 0)
                    <div class="mb-2">
                        <label class="form-label text-muted">Photos actuelles :</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($service->photos as $index => $photo)
                                <div class="position-relative" style="width: 100px;">
                                    <img src="{{ asset('storage/' . $photo) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    <div class="form-check mt-1">
                                        <input type="checkbox" name="delete_photos[]" value="{{ $index }}" class="form-check-input" id="delete_photo_{{ $index }}">
                                        <label class="form-check-label small text-danger" for="delete_photo_{{ $index }}">Suppr.</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <input type="file" name="photos[]" id="photos" class="form-control @error('photos') is-invalid @enderror @error('photos.*') is-invalid @enderror" multiple accept="image/*">
                <small class="text-muted">Ajoutez de nouvelles images (JPG, PNG, max 2Mo chacune)</small>
                @error('photos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @error('photos.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label d-block">Employés assignés</label>
                <div class="border rounded p-2 p-md-3" style="max-height: 250px; overflow-y: auto;">
                    @php $selectedEmployees = old('employees', $service->employees->pluck('id')->toArray()); @endphp
                    <div class="row">
                        @forelse($employees as $employee)
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="form-check py-1">
                                    <input type="checkbox" name="employees[]" value="{{ $employee->id }}"
                                           id="employee_{{ $employee->id }}"
                                           class="form-check-input"
                                           @if(in_array($employee->id, $selectedEmployees)) checked @endif>
                                    <label class="form-check-label" for="employee_{{ $employee->id }}">
                                        {{ $employee->name }}
                                    </label>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted mb-0">Aucun employé disponible</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <small class="text-muted d-block mt-1">Cochez les employés qui peuvent réaliser ce service</small>
                @error('employees')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" id="active" class="form-check-input" value="1" @if(old('active', $service->active)) checked @endif>
                <label for="active" class="form-check-label">Service actif</label>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
    </div>
</div>
@endsection
