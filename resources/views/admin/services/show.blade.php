@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-scissors"></i></div>
                <div>
                    <h2 class="beauty-page-title">{{ $service->name }}</h2>
                    <p class="beauty-page-subtitle">Détails du service</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-primary"><i class="fa fa-pencil mr-2"></i>Modifier</a>
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
            </div>
        </div>

        @include('partials.success')
        @include('partials.error')

        <div class="row">
            <div class="col-lg-8">
                @if($service->photos && count($service->photos) > 0)
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h5 class="mb-0"><i class="fa fa-image mr-2" style="color:var(--primary);"></i>Photos</h5>
                    </div>
                    <div class="beauty-card-body">
                        <div class="row">
                            @foreach($service->photos as $photo)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/' . $photo) }}" alt="{{ $service->name }}" class="img-fluid" style="border-radius:12px;width:100%;height:200px;object-fit:cover;">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h5 class="mb-0"><i class="fa fa-info-circle mr-2" style="color:var(--primary);"></i>Informations</h5>
                    </div>
                    <div class="beauty-card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" style="width:180px;">Nom</td>
                                    <td><strong>{{ $service->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Catégorie</td>
                                    <td>
                                        @if($service->category)
                                            <span class="badge badge-info">{{ ucfirst($service->category) }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Public</td>
                                    <td><span class="badge badge-secondary">{{ ucfirst($service->gender ?? 'mixte') }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Prix</td>
                                    <td>
                                        @if($service->hasActivePromotion())
                                            <span class="text-muted" style="text-decoration:line-through;">{{ number_format($service->price, 0, ',', ' ') }} FCFA</span>
                                            <strong class="text-danger ml-2">{{ number_format($service->promotion_price, 0, ',', ' ') }} FCFA</strong>
                                            <span class="badge badge-danger ml-1">-{{ $service->getDiscountPercentage() }}%</span>
                                        @else
                                            <strong class="text-primary">{{ number_format($service->price, 0, ',', ' ') }} FCFA</strong>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Durée</td>
                                    <td><span class="badge badge-secondary">{{ $service->duration }} min</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Statut</td>
                                    <td>
                                        @if($service->active)
                                            <span class="badge badge-success"><i class="fa fa-check mr-1"></i>Actif</span>
                                        @else
                                            <span class="badge badge-danger"><i class="fa fa-times mr-1"></i>Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        @if($service->description)
                            <hr>
                            <h6 class="text-muted mb-2">Description</h6>
                            <p>{{ $service->description }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                @if($service->employees && $service->employees->count() > 0)
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h5 class="mb-0"><i class="fa fa-users mr-2" style="color:var(--primary);"></i>Employés assignés</h5>
                    </div>
                    <div class="beauty-card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($service->employees as $employee)
                            <li class="mb-2">
                                <i class="fa fa-check-circle text-success mr-2"></i>
                                {{ $employee->name }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
