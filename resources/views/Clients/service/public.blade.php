@extends('master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Catalogue des Services</h4>
                    <p class="text-muted">Découvrez tous nos services de coiffure</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('client.login') }}" class="btn btn-primary">
                    <i class="fa fa-calendar mr-2"></i>Prendre rendez-vous
                </a>
            </div>
        </div>

        @forelse($services as $category => $categoryServices)
        <div class="card mb-4">
            <div class="card-header bg-primary">
                <h4 class="card-title text-white mb-0">
                    <i class="fa fa-tag mr-2"></i>{{ $category ?: 'Services Généraux' }}
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($categoryServices as $service)
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="border rounded p-3 h-100">
                            <h5>{{ $service->name }}</h5>
                            <p class="text-muted small mb-2">{{ $service->description ?? '' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-primary mb-0">{{ $service->price }}€</span>
                                <span class="badge badge-secondary">{{ $service->duration }} min</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fa fa-cut fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun service disponible</h5>
                <p class="text-muted">Revenez bientôt pour découvrir nos prestations</p>
            </div>
        </div>
        @endforelse

        <div class="card mt-4">
            <div class="card-body text-center">
                <h4>Prêt à prendre rendez-vous ?</h4>
                <p class="text-muted">Créez un compte ou connectez-vous pour réserver</p>
                <a href="{{ route('client.register') }}" class="btn btn-primary btn-lg mr-2">
                    <i class="fa fa-user-plus mr-2"></i>S'inscrire
                </a>
                <a href="{{ route('client.login') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fa fa-sign-in mr-2"></i>Se connecter
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
