@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-scissors"></i></div>
                <div>
                    <h2 class="beauty-page-title">Services </h2>
                    <p class="beauty-page-subtitle">Gérez les prestations proposées par le salon</p>
                </div>
            </div>
            <a href="{{ route('admin.services.create') }}" class="beauty-btn-primary">
                <i class="fa fa-plus mr-2"></i>Ajouter un service
            </a>
        </div>

        @include('partials.success')
        @include('partials.error')

        <div class="row mb-4">
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon rose"><i class="fa fa-scissors"></i></div>
                    <div>
                        <h3>{{ $services->total() ?? $services->count() }}</h3>
                        <p>Total services</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon green"><i class="fa fa-check-circle"></i></div>
                    <div>
                        <h3>{{ $services->where('active', true)->count() }}</h3>
                        <p>Services actifs</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon gold"><i class="fa fa-money"></i></div>
                    <div>
                        <h3>{{ number_format($services->avg('price') ?? 0, 0, ',', ' ') }}</h3>
                        <p>Prix moyen (FCFA)</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="beauty-card mb-4">
            <div class="beauty-card-header">
                <h4><i class="fa fa-list mr-2" style="color:var(--primary);"></i>Tous les services</h4>
            </div>
            <div class="beauty-card-body">
                @if($services->isEmpty())
                <div class="beauty-empty">
                    <div class="beauty-empty-icon"><i class="fa fa-scissors"></i></div>
                    <p>Aucun service trouvé</p>
                    <a href="{{ route('admin.services.create') }}" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Ajouter un service</a>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width:80px;">Photo</th>
                                <th>Service</th>
                                <th>Catégorie</th>
                                <th>Public</th>
                                <th class="text-center">Prix</th>
                                <th class="text-center">Durée</th>
                                <th class="text-center">Statut</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                            <tr>
                                <td>
                                    @if($service->photos && count($service->photos) > 0)
                                        <img src="{{ asset('storage/' . $service->photos[0]) }}" alt="{{ $service->name }}" class="beauty-thumb">
                                    @else
                                        <div class="beauty-thumb-placeholder"><i class="fa fa-image"></i></div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $service->name }}</strong>
                                    @if($service->description)
                                    <br><small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                    @endif
                                    @if($service->photos && count($service->photos) > 1)
                                    <br><small style="color:var(--primary);"><i class="fa fa-images mr-1"></i>{{ count($service->photos) }} photos</small>
                                    @endif
                                </td>
                                <td>
                                    @if($service->category)
                                    <span class="badge badge-info">{{ ucfirst($service->category) }}</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $genderColors = ['homme' => 'primary', 'femme' => 'danger', 'enfant' => 'warning', 'mixte' => 'secondary'];
                                        $genderIcons = ['homme' => 'fa-male', 'femme' => 'fa-female', 'enfant' => 'fa-child', 'mixte' => 'fa-users'];
                                    @endphp
                                    <span class="badge badge-{{ $genderColors[$service->gender] ?? 'secondary' }}">
                                        <i class="fa {{ $genderIcons[$service->gender] ?? 'fa-users' }} mr-1"></i>
                                        {{ ucfirst($service->gender ?? 'mixte') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($service->hasActivePromotion())
                                        <span class="text-muted text-decoration-line-through small">{{ number_format($service->price, 0, ',', ' ') }}</span>
                                        <br><strong class="text-danger">{{ number_format($service->promotion_price, 0, ',', ' ') }}</strong> <small>FCFA</small>
                                        <br><span class="badge badge-danger badge-sm">-{{ $service->getDiscountPercentage() }}%</span>
                                    @else
                                        <strong class="text-primary">{{ number_format($service->price, 0, ',', ' ') }}</strong> <small>FCFA</small>
                                    @endif
                                </td>
                                <td class="text-center"><span class="badge badge-secondary">{{ $service->duration }} min</span></td>
                                <td class="text-center">
                                    @if($service->active)
                                    <span class="badge badge-success"><i class="fa fa-check mr-1"></i>Actif</span>
                                    @else
                                    <span class="badge badge-danger"><i class="fa fa-times mr-1"></i>Inactif</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-primary" title="Modifier"><i class="fa fa-pencil"></i></a>
                                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline confirm-delete" data-confirm-message="Supprimer ce service ?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            @if($services->hasPages())
            <div class="beauty-card-footer">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="text-muted small">Affichage de {{ $services->firstItem() }} à {{ $services->lastItem() }} sur {{ $services->total() }} services</div>
                    <div>{{ $services->links() }}</div>
                </div>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
