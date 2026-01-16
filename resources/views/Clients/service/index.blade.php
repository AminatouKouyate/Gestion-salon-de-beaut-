@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Nos Services</h4>
                    <p class="text-muted">Découvrez tous nos services de beauté</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('client.dashboard') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        {{-- Filtres --}}
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-title mb-0"><i class="fa fa-filter mr-2"></i>Filtrer les services</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('client.services') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="category">Catégorie</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Toutes les catégories</option>
                                    @foreach($categories as $category)
                                        @if($category)
                                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="min_price">Prix min (FCFA)</label>
                                <input type="number" name="min_price" id="min_price" class="form-control"
                                       value="{{ request('min_price') }}" min="0" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="max_price">Prix max (FCFA)</label>
                                <input type="number" name="max_price" id="max_price" class="form-control"
                                       value="{{ request('max_price') }}" min="0" placeholder="500">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="min_duration">Durée min (min)</label>
                                <input type="number" name="min_duration" id="min_duration" class="form-control"
                                       value="{{ request('min_duration') }}" min="0" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="max_duration">Durée max (min)</label>
                                <input type="number" name="max_duration" id="max_duration" class="form-control"
                                       value="{{ request('max_duration') }}" min="0" placeholder="180">
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @if(request()->hasAny(['category', 'min_price', 'max_price', 'min_duration', 'max_duration']))
                    <div class="mt-2">
                        <a href="{{ route('client.services') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa fa-times mr-1"></i>Réinitialiser les filtres
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Liste des services --}}
        <div class="row">
            @forelse($services as $service)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    @if($service->hasActivePromotion())
                    <div class="ribbon ribbon-top-right"><span>-{{ $service->getDiscountPercentage() }}%</span></div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">{{ $service->name }}</h5>
                            <span class="badge badge-primary">{{ $service->category ?? 'Général' }}</span>
                        </div>

                        <p class="card-text text-muted">
                            {{ Str::limit($service->description ?? 'Découvrez ce service dans notre salon.', 100) }}
                        </p>

                        <div class="mb-3">
                            <span class="text-muted"><i class="fa fa-clock-o mr-1"></i>{{ $service->duration }} min</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if($service->hasActivePromotion())
                                    <span class="text-muted text-decoration-line-through">{{ $service->price }} FCFA</span>
                                    <span class="h4 text-danger ml-1">{{ $service->promotion_price }} FCFA</span>
                                @else
                                    <span class="h4 text-primary">{{ $service->price }} FCFA</span>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('client.services.show', $service) }}" class="btn btn-outline-info btn-sm mr-1">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('client.appointments.create', ['service' => $service->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-calendar-plus-o mr-1"></i>Réserver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fa fa-cut fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-3">Aucun service ne correspond à vos critères</p>
                        <a href="{{ route('client.services') }}" class="btn btn-primary">
                            Voir tous les services
                        </a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center">
            {{ $services->links() }}
        </div>
    </div>
</div>

<style>
.ribbon {
    position: absolute;
    right: -5px;
    top: -5px;
    z-index: 1;
    overflow: hidden;
    width: 75px;
    height: 75px;
    text-align: right;
}
.ribbon span {
    font-size: 10px;
    font-weight: bold;
    color: #FFF;
    text-transform: uppercase;
    text-align: center;
    line-height: 20px;
    transform: rotate(45deg);
    -webkit-transform: rotate(45deg);
    width: 100px;
    display: block;
    background: #dc3545;
    position: absolute;
    top: 19px;
    right: -21px;
}
.text-decoration-line-through {
    text-decoration: line-through;
}
</style>
@endsection
