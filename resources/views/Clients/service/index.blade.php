@extends('master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Nos Services</h4>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($services as $service)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">{{ $service->name }}</h5>
                            <span class="badge badge-primary">{{ $service->category ?? 'Général' }}</span>
                        </div>
                        <p class="card-text text-muted">{{ $service->description ?? 'Découvrez ce service dans notre salon.' }}</p>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="h4 text-primary">{{ $service->price }}€</span>
                                <small class="text-muted ml-2">/ {{ $service->duration }} min</small>
                            </div>
                            <a href="{{ route('appointments.create', ['service' => $service->id]) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-calendar-plus-o mr-1"></i>Réserver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fa fa-cut fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun service disponible pour le moment</p>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        {{ $services->links() }}
    </div>
</div>
@endsection
