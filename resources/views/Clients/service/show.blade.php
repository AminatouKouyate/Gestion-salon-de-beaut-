{{--
    Vue : Détails d'un service
    Description : Page de détail d'un service avec galerie de photos (navigation par flèches et swipe), description, prix, durée, promotions actives, employés disponibles et bouton de réservation.
--}}
@extends('layouts.client-master')

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
            <a href="{{ route('client.services') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                {{-- Galerie photos du service --}}
                @if($service->photos && count($service->photos) > 0)
                <div class="card mb-3">
                    <div class="card-body p-0">
                        <div class="show-gallery-container position-relative" style="background: #000; border-radius: 8px; overflow: hidden;">
                            <div class="show-gallery-track" id="showGalleryTrack"
                                 style="display: flex; transition: transform 0.3s ease; height: 400px;">
                                @foreach($service->photos as $i => $photo)
                                <div class="show-gallery-slide" style="min-width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ asset('storage/' . $photo) }}" alt="{{ $service->name }} - Photo {{ $i + 1 }}"
                                         style="max-width: 100%; max-height: 100%; object-fit: contain; cursor: zoom-in;"
                                         class="show-photo-zoomable" data-index="{{ $i }}">
                                </div>
                                @endforeach
                            </div>

                            @if(count($service->photos) > 1)
                            <button class="gallery-nav gallery-prev" onclick="showGalleryNav(-1)">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <button class="gallery-nav gallery-next" onclick="showGalleryNav(1)">
                                <i class="fa fa-chevron-right"></i>
                            </button>

                            <div class="position-absolute text-white" id="showGalleryCounter"
                                 style="bottom: 15px; left: 50%; transform: translateX(-50%); z-index: 10;
                                        background: rgba(0,0,0,0.6); padding: 4px 14px; border-radius: 20px; font-size: 0.85rem;">
                                1 / {{ count($service->photos) }}
                            </div>

                            <div class="position-absolute d-flex justify-content-center" id="showGalleryDots"
                                 style="bottom: 50px; left: 50%; transform: translateX(-50%); z-index: 10; gap: 6px;">
                                @foreach($service->photos as $i => $photo)
                                <button class="gallery-dot {{ $i === 0 ? 'active' : '' }}" onclick="showGalleryGoTo({{ $i }})"></button>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        {{-- Miniatures --}}
                        @if(count($service->photos) > 1)
                        <div class="d-flex p-2" style="gap: 8px; overflow-x: auto;">
                            @foreach($service->photos as $i => $photo)
                            <img src="{{ asset('storage/' . $photo) }}" alt="Miniature {{ $i + 1 }}"
                                 class="show-thumbnail {{ $i === 0 ? 'active' : '' }}"
                                 onclick="showGalleryGoTo({{ $i }})"
                                 style="width: 70px; height: 70px; object-fit: cover; border-radius: 6px; cursor: pointer;
                                        border: 2px solid transparent; opacity: 0.6; transition: all 0.2s;">
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endif

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
                                    <i class="fa fa-money fa-2x text-primary mb-2"></i>
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
.gallery-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: rgba(255,255,255,0.2);
    border: none;
    color: #fff;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
}
.gallery-nav:hover { background: rgba(255,255,255,0.4); }
.gallery-prev { left: 12px; }
.gallery-next { right: 12px; }
.gallery-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: rgba(255,255,255,0.4);
    transition: all 0.3s; cursor: pointer;
    border: none; padding: 0;
}
.gallery-dot.active {
    background: #fff; width: 24px; border-radius: 4px;
}
.show-thumbnail.active {
    border-color: #007bff !important;
    opacity: 1 !important;
}
</style>

@push('scripts')
<script>
(function() {
    var showIndex = 0;
    var totalPhotos = {{ $service->photos ? count($service->photos) : 0 }};
    var track = document.getElementById('showGalleryTrack');
    var counter = document.getElementById('showGalleryCounter');
    var dotsContainer = document.getElementById('showGalleryDots');

    if (!track || totalPhotos <= 0) return;

    window.showGalleryGoTo = function(index) {
        if (index < 0) index = totalPhotos - 1;
        if (index >= totalPhotos) index = 0;
        showIndex = index;

        track.style.transform = 'translateX(-' + (showIndex * 100) + '%)';
        if (counter) counter.textContent = (showIndex + 1) + ' / ' + totalPhotos;

        var dots = dotsContainer ? dotsContainer.querySelectorAll('.gallery-dot') : [];
        dots.forEach(function(d, i) { d.classList.toggle('active', i === showIndex); });

        var thumbs = document.querySelectorAll('.show-thumbnail');
        thumbs.forEach(function(t, i) { t.classList.toggle('active', i === showIndex); });
    };

    window.showGalleryNav = function(dir) {
        showGalleryGoTo(showIndex + dir);
    };

    // Swipe tactile
    var container = track.parentElement;
    var startX = 0, isDragging = false, diffX = 0;

    container.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        isDragging = true;
        track.style.transition = 'none';
    }, { passive: true });

    container.addEventListener('touchmove', function(e) {
        if (!isDragging) return;
        diffX = e.touches[0].clientX - startX;
        track.style.transform = 'translateX(' + (-(showIndex * container.offsetWidth) + diffX) + 'px)';
    }, { passive: true });

    container.addEventListener('touchend', function() {
        if (!isDragging) return;
        isDragging = false;
        track.style.transition = 'transform 0.3s ease';
        if (Math.abs(diffX) > 50) {
            showGalleryGoTo(diffX < 0 ? showIndex + 1 : showIndex - 1);
        } else {
            showGalleryGoTo(showIndex);
        }
        diffX = 0;
    });
})();
</script>
@endpush
@endsection
