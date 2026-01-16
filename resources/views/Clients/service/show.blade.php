@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>{{ $service->name }}</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <span class="badge badge-primary mb-2">{{ $service->category ?? 'Général' }}</span>
                            <h2>{{ $service->name }}</h2>
                        </div>

                        <p class="lead">{{ $service->description ?? 'Découvrez ce service exceptionnel proposé par notre salon.' }}</p>

                        <hr>

                        @if($service->hasActivePromotion())
                        <div class="alert alert-danger mb-4">
                            <strong><i class="fa fa-fire mr-2"></i>{{ $service->promotion_label ?? 'Promotion en cours' }}</strong>
                            <span class="ml-2">-{{ $service->getDiscountPercentage() }}%</span>
                            @if($service->promotion_end)
                            <small class="d-block mt-1">Valable jusqu'au {{ $service->promotion_end->format('d/m/Y') }}</small>
                            @endif
                        </div>
                        @endif

                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3">
                                    <i class="fa fa-euro fa-2x text-primary mb-2"></i>
                                    @if($service->hasActivePromotion())
                                        <h4>
                                            <span class="text-muted text-decoration-line-through">{{ $service->price }} FCFA</span>
                                            <span class="text-danger">{{ $service->promotion_price }} FCFA</span>
                                        </h4>
                                    @else
                                        <h4>{{ $service->price }} FCFA</h4>
                                    @endif
                                    <p class="text-muted mb-0">Prix</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3">
                                    <i class="fa fa-clock-o fa-2x text-primary mb-2"></i>
                                    <h4>{{ $service->duration }} min</h4>
                                    <p class="text-muted mb-0">Durée</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3">
                                    <i class="fa fa-star fa-2x text-primary mb-2"></i>
                                    <h4>4.8/5</h4>
                                    <p class="text-muted mb-0">Note moyenne</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h4 class="card-title text-white mb-0">Réserver ce service</h4>
                    </div>
                    <div class="card-body text-center">
                        @if($service->hasActivePromotion())
                            <p class="mb-1"><span class="text-muted text-decoration-line-through">{{ $service->price }} FCFA</span></p>
                            <p class="h3 text-danger mb-3">{{ $service->promotion_price }} FCFA</p>
                        @else
                            <p class="h3 text-primary mb-3">{{ $service->price }} FCFA</p>
                        @endif
                        <p class="text-muted">Durée estimée: {{ $service->duration }} minutes</p>
                        @auth('clients')
                            <a href="{{ route('client.appointments.create', ['service' => $service->id]) }}" class="btn btn-primary btn-lg btn-block">
                                <i class="fa fa-calendar-plus-o mr-2"></i>Réserver maintenant
                            </a>
                        @else
                            <a href="{{ route('client.login') }}" class="btn btn-primary btn-lg btn-block">
                                <i class="fa fa-sign-in mr-2"></i>Se connecter pour réserver
                            </a>
                            <p class="mt-3 mb-0">
                                <small>Pas encore de compte ? <a href="{{ route('client.register') }}">S'inscrire</a></small>
                            </p>
                        @endauth
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h5><i class="fa fa-info-circle mr-2 text-primary"></i>Informations</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fa fa-check text-success mr-2"></i>Service professionnel</li>
                            <li class="mb-2"><i class="fa fa-check text-success mr-2"></i>Produits de qualité</li>
                            <li><i class="fa fa-check text-success mr-2"></i>Satisfaction garantie</li>
                        </ul>
                    </div>
                </div>

                @if($service->employees && $service->employees->count() > 0)
                <div class="card mt-3">
                    <div class="card-body">
                        <h5><i class="fa fa-users mr-2 text-primary"></i>Employés disponibles</h5>
                        <ul class="list-unstyled mb-0">
                            @foreach($service->employees as $employee)
                                <li class="mb-2">
                                    <i class="fa fa-user text-primary mr-2"></i>{{ $employee->name }}
                                    @if($employee->specialties)
                                        <small class="text-muted">- {{ $employee->specialties }}</small>
                                    @endif
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
<style>
.text-decoration-line-through {
    text-decoration: line-through;
}
</style>
@endsection
