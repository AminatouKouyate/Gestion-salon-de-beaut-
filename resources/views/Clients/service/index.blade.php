{{--
    Vue : Catalogue des services - Espace client
    Description : Liste filtrée des services du salon avec filtres par catégorie, prix, durée et genre. Cartes de services avec photos (galerie modal), promotions, prix et bouton de réservation.
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-scissors"></i></div>
                <div>
                    <h2 class="beauty-page-title">Nos Services</h2>
                    <p class="beauty-page-subtitle">Découvrez tous nos services de beauté</p>
                </div>
            </div>
            <a href="{{ route('client.dashboard') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
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

        {{-- Filtres par genre --}}
        <div class="mb-4">
            <div class="btn-group" role="group">
                <a href="{{ route('client.services', array_merge(request()->except('gender'), [])) }}" 
                   class="btn {{ !request('gender') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fa fa-users mr-1"></i>Tous
                </a>
                <a href="{{ route('client.services', array_merge(request()->all(), ['gender' => 'femme'])) }}" 
                   class="btn {{ request('gender') == 'femme' ? 'btn-danger' : 'btn-outline-danger' }}">
                    <i class="fa fa-female mr-1"></i>Femme
                </a>
                <a href="{{ route('client.services', array_merge(request()->all(), ['gender' => 'homme'])) }}" 
                   class="btn {{ request('gender') == 'homme' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fa fa-male mr-1"></i>Homme
                </a>
                <a href="{{ route('client.services', array_merge(request()->all(), ['gender' => 'enfant'])) }}" 
                   class="btn {{ request('gender') == 'enfant' ? 'btn-warning' : 'btn-outline-warning' }}">
                    <i class="fa fa-child mr-1"></i>Enfant
                </a>
            </div>
        </div>

        {{-- Liste des services --}}
        <div class="row">
            @forelse($services as $service)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 service-card shadow-sm">
                    @if($service->hasActivePromotion())
                    <div class="ribbon ribbon-top-right"><span>-{{ $service->getDiscountPercentage() }}%</span></div>
                    @endif
                    
                    {{-- Image du service (cliquable pour galerie) --}}
                    <div class="service-image-container position-relative">
                        @if($service->photos && count($service->photos) > 0)
                            <img src="{{ asset('storage/' . $service->photos[0]) }}" 
                                 alt="{{ $service->name }}" 
                                 class="card-img-top service-image service-photo-clickable"
                                 data-photos='@json($service->photos)'
                                 data-service-name="{{ $service->name }}"
                                 style="cursor: pointer;">
                            @if(count($service->photos) > 1)
                                <span class="badge badge-dark position-absolute photo-count-badge"
                                      style="bottom: 10px; right: 10px; pointer-events: none;">
                                    <i class="fa fa-camera mr-1"></i>{{ count($service->photos) }}
                                </span>
                            @endif
                        @else
                            <div class="service-image-placeholder d-flex align-items-center justify-content-center bg-gradient-light">
                                <i class="fa fa-scissors fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        {{-- Badge genre --}}
                        @php
                            $genderColors = ['homme' => 'primary', 'femme' => 'danger', 'enfant' => 'warning', 'mixte' => 'secondary'];
                            $genderIcons = ['homme' => 'fa-male', 'femme' => 'fa-female', 'enfant' => 'fa-child', 'mixte' => 'fa-users'];
                        @endphp
                        <span class="badge badge-{{ $genderColors[$service->gender] ?? 'secondary' }} position-absolute" style="top: 10px; left: 10px;">
                            <i class="fa {{ $genderIcons[$service->gender] ?? 'fa-users' }} mr-1"></i>
                            {{ ucfirst($service->gender ?? 'Mixte') }}
                        </span>
                        @if($service->hasActivePromotion())
                            <span class="badge badge-danger position-absolute" style="top: 10px; right: 10px; font-size: 0.9em;">
                                <i class="fa fa-tag mr-1"></i>-{{ $service->getDiscountPercentage() }}%
                            </span>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $service->name }}</h5>
                            <span class="badge badge-info">{{ ucfirst($service->category ?? 'Général') }}</span>
                        </div>

                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($service->description ?? 'Découvrez ce service dans notre salon.', 80) }}
                        </p>

                        <div class="mb-3">
                            <span class="text-muted"><i class="fa fa-clock-o mr-1"></i>{{ $service->duration }} min</span>
                        </div>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div>
                                @if($service->hasActivePromotion())
                                    <span class="text-muted text-decoration-line-through small">{{ number_format($service->price, 0, ',', ' ') }} FCFA</span>
                                    <br><span class="h5 text-danger mb-0">{{ number_format($service->promotion_price, 0, ',', ' ') }} FCFA</span>
                                @else
                                    <span class="h5 text-primary mb-0">{{ number_format($service->price, 0, ',', ' ') }} FCFA</span>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('client.appointments.create', ['service' => $service->id]) }}" class="btn btn-primary">
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

{{-- Modal Galerie Photos Style SHEIN (doit rester dans content) --}}
<div class="modal fade" id="photoGalleryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 95vw;">
        <div class="modal-content" style="background: #000; border: none; border-radius: 0;">
            <div class="modal-body p-0 position-relative" style="min-height: 80vh;">
                {{-- Bouton fermer --}}
                <button type="button" class="close position-absolute text-white" data-dismiss="modal" aria-label="Fermer"
                        style="top: 15px; right: 20px; z-index: 10; opacity: 1; font-size: 2rem; text-shadow: 0 0 10px rgba(0,0,0,0.8);">
                    <span aria-hidden="true">&times;</span>
                </button>

                {{-- Nom du service --}}
                <div class="position-absolute text-white px-3 py-2" id="galleryServiceName"
                     style="top: 15px; left: 20px; z-index: 10; font-size: 1.1rem; font-weight: 600; text-shadow: 0 0 10px rgba(0,0,0,0.8);">
                </div>

                {{-- Conteneur images avec swipe --}}
                <div id="galleryContainer" style="width: 100%; height: 80vh; position: relative; overflow: hidden;">
                    <div id="galleryTrack" style="display: flex; height: 100%; transition: transform 0.3s ease;">
                    </div>
                </div>

                {{-- Flèche gauche --}}
                <button class="gallery-nav gallery-prev" id="galleryPrev" style="display:none;">
                    <i class="fa fa-chevron-left"></i>
                </button>
                {{-- Flèche droite --}}
                <button class="gallery-nav gallery-next" id="galleryNext" style="display:none;">
                    <i class="fa fa-chevron-right"></i>
                </button>

                {{-- Compteur style SHEIN (1/5) --}}
                <div class="position-absolute text-white" id="galleryCounter"
                     style="bottom: 60px; left: 50%; transform: translateX(-50%); z-index: 10;
                            background: rgba(0,0,0,0.6); padding: 6px 16px; border-radius: 20px; font-size: 0.9rem;">
                </div>

                {{-- Points indicateurs --}}
                <div class="position-absolute d-flex justify-content-center" id="galleryDots"
                     style="bottom: 25px; left: 50%; transform: translateX(-50%); z-index: 10; gap: 6px;">
                </div>
            </div>
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
.service-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
    border-radius: 12px;
}
.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
.service-image {
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.service-card:hover .service-image {
    transform: scale(1.05);
}
.service-image-container {
    overflow: hidden;
    height: 200px;
}
.service-image-placeholder {
    height: 200px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}
.bg-gradient-light {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

/* Galerie Style SHEIN */
.service-photo-clickable:hover {
    opacity: 0.85;
}
.photo-count-badge {
    font-size: 0.8rem;
    padding: 4px 10px;
}
#photoGalleryModal .modal-dialog {
    max-width: calc(100vw - 300px);
    margin-right: 20px;
}
#photoGalleryModal .modal-content {
    border-radius: 12px;
    overflow: hidden;
}
@media (max-width: 768px) {
    #photoGalleryModal .modal-dialog {
        max-width: 95vw;
        margin: 10px auto;
    }
}
.gallery-slide {
    min-width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    user-select: none;
}
.gallery-slide img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.gallery-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: rgba(255,255,255,0.2);
    border: none;
    color: #fff;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    font-size: 1.2rem;
    cursor: pointer;
    transition: background 0.2s;
    backdrop-filter: blur(4px);
}
.gallery-nav:hover {
    background: rgba(255,255,255,0.4);
}
.gallery-prev { left: 15px; }
.gallery-next { right: 15px; }
.gallery-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,0.4);
    transition: all 0.3s;
    cursor: pointer;
    border: none;
    padding: 0;
}
.gallery-dot.active {
    background: #fff;
    width: 24px;
    border-radius: 4px;
}
</style>
@endsection

@push('scripts')
<script>
(function() {
    var currentIndex = 0;
    var photos = [];
    var track = document.getElementById('galleryTrack');
    var container = document.getElementById('galleryContainer');
    var counter = document.getElementById('galleryCounter');
    var dotsContainer = document.getElementById('galleryDots');
    var prevBtn = document.getElementById('galleryPrev');
    var nextBtn = document.getElementById('galleryNext');
    var serviceName = document.getElementById('galleryServiceName');

    function openGallery(photosList, name, startIndex) {
        photos = photosList;
        currentIndex = startIndex || 0;
        serviceName.textContent = name;

        track.innerHTML = '';
        dotsContainer.innerHTML = '';

        photos.forEach(function(photo, i) {
            var slide = document.createElement('div');
            slide.className = 'gallery-slide';
            var img = document.createElement('img');
            img.src = '/storage/' + photo;
            img.alt = name + ' - Photo ' + (i + 1);
            img.draggable = false;
            slide.appendChild(img);
            track.appendChild(slide);

            var dot = document.createElement('button');
            dot.className = 'gallery-dot' + (i === currentIndex ? ' active' : '');
            dot.onclick = function() { goTo(i); };
            dotsContainer.appendChild(dot);
        });

        if (photos.length > 1) {
            prevBtn.style.display = 'flex';
            prevBtn.style.alignItems = 'center';
            prevBtn.style.justifyContent = 'center';
            nextBtn.style.display = 'flex';
            nextBtn.style.alignItems = 'center';
            nextBtn.style.justifyContent = 'center';
        } else {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
        }

        updateGallery();
        $('#photoGalleryModal').modal('show');
    }

    function goTo(index) {
        if (index < 0) index = photos.length - 1;
        if (index >= photos.length) index = 0;
        currentIndex = index;
        updateGallery();
    }

    function updateGallery() {
        track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
        counter.textContent = (currentIndex + 1) + ' / ' + photos.length;

        var dots = dotsContainer.querySelectorAll('.gallery-dot');
        dots.forEach(function(dot, i) {
            dot.classList.toggle('active', i === currentIndex);
        });
    }

    prevBtn.onclick = function() { goTo(currentIndex - 1); };
    nextBtn.onclick = function() { goTo(currentIndex + 1); };

    // Support clavier
    document.addEventListener('keydown', function(e) {
        if (!$('#photoGalleryModal').hasClass('show')) return;
        if (e.key === 'ArrowLeft') goTo(currentIndex - 1);
        if (e.key === 'ArrowRight') goTo(currentIndex + 1);
        if (e.key === 'Escape') $('#photoGalleryModal').modal('hide');
    });

    // Support swipe tactile (style SHEIN)
    var startX = 0, startY = 0, isDragging = false, diffX = 0;

    container.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        isDragging = true;
        track.style.transition = 'none';
    }, { passive: true });

    container.addEventListener('touchmove', function(e) {
        if (!isDragging) return;
        diffX = e.touches[0].clientX - startX;
        var diffY = e.touches[0].clientY - startY;
        if (Math.abs(diffX) > Math.abs(diffY)) {
            var offset = -(currentIndex * container.offsetWidth) + diffX;
            track.style.transform = 'translateX(' + offset + 'px)';
        }
    }, { passive: true });

    container.addEventListener('touchend', function() {
        if (!isDragging) return;
        isDragging = false;
        track.style.transition = 'transform 0.3s ease';
        if (Math.abs(diffX) > 50) {
            if (diffX < 0) goTo(currentIndex + 1);
            else goTo(currentIndex - 1);
        } else {
            updateGallery();
        }
        diffX = 0;
    });

    // Support souris (glisser-déposer desktop)
    container.addEventListener('mousedown', function(e) {
        startX = e.clientX;
        isDragging = true;
        track.style.transition = 'none';
        e.preventDefault();
    });

    container.addEventListener('mousemove', function(e) {
        if (!isDragging) return;
        diffX = e.clientX - startX;
        var offset = -(currentIndex * container.offsetWidth) + diffX;
        track.style.transform = 'translateX(' + offset + 'px)';
    });

    container.addEventListener('mouseup', function() {
        if (!isDragging) return;
        isDragging = false;
        track.style.transition = 'transform 0.3s ease';
        if (Math.abs(diffX) > 50) {
            if (diffX < 0) goTo(currentIndex + 1);
            else goTo(currentIndex - 1);
        } else {
            updateGallery();
        }
        diffX = 0;
    });

    container.addEventListener('mouseleave', function() {
        if (isDragging) {
            isDragging = false;
            track.style.transition = 'transform 0.3s ease';
            updateGallery();
        }
    });

    // Clic sur image de service
    $(document).on('click', '.service-photo-clickable', function() {
        var photoData = $(this).data('photos');
        var name = $(this).data('service-name');
        openGallery(photoData, name, 0);
    });
})();
</script>
@endpush
