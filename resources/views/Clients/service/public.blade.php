{{--
    Vue : Catalogue des services - Page publique
    Description : Page publique accessible sans connexion affichant les services groupés par catégorie avec photos, prix et boutons d'inscription/connexion pour réserver.
--}}
@extends('layouts.public')

@section('title', 'Nos Services')

@section('content')
<div class="container" style="max-width:1200px;padding-top:20px;">
        {{-- Hero mini --}}
        <div class="text-center mb-5">
            <span style="display:inline-block;padding:6px 20px;background:var(--primary-soft);color:var(--primary);border-radius:20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:2px;margin-bottom:14px;">Nos Prestations</span>
            <h2 style="font-family:'Playfair Display',serif;font-size:36px;color:var(--dark);margin-bottom:10px;">Catalogue des Services</h2>
            <p style="color:#8E8E8E;font-size:16px;">Découvrez tous nos services de beauté et réservez en ligne</p>
            <a href="{{ route('client.login') }}" style="display:inline-block;margin-top:16px;padding:12px 30px;background:linear-gradient(135deg,var(--primary),var(--dark));color:white;border-radius:14px;text-decoration:none;font-weight:600;transition:all 0.3s;">
                <i class="fa fa-calendar mr-2"></i>Prendre rendez-vous
            </a>
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
                        <div class="card h-100 shadow-sm service-card-public">
                            @if($service->photos && count($service->photos) > 0)
                            <div class="position-relative" style="overflow: hidden; height: 200px;">
                                <img src="{{ asset('storage/' . $service->photos[0]) }}" 
                                     alt="{{ $service->name }}"
                                     class="card-img-top service-photo-clickable"
                                     data-photos='@json($service->photos)'
                                     data-service-name="{{ $service->name }}"
                                     style="height: 200px; object-fit: cover; cursor: pointer; transition: transform 0.3s;">
                                @if(count($service->photos) > 1)
                                <span class="badge badge-dark position-absolute" style="bottom: 8px; right: 8px; pointer-events: none;">
                                    <i class="fa fa-camera mr-1"></i>{{ count($service->photos) }}
                                </span>
                                @endif
                            </div>
                            @else
                            <div class="d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #f5f7fa, #c3cfe2);">
                                <i class="fa fa-scissors fa-3x text-muted"></i>
                            </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $service->name }}</h5>
                                <p class="text-muted small mb-2">{{ Str::limit($service->description ?? '', 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 text-primary mb-0">{{ number_format($service->price, 0, ',', ' ') }} FCFA</span>
                                    <span class="badge badge-secondary">{{ $service->duration }} min</span>
                                </div>
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

{{-- Modal Galerie Photos --}}
<div class="modal fade" id="photoGalleryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 90vw;">
        <div class="modal-content" style="background: #000; border: none; border-radius: 12px; overflow: hidden;">
            <div class="modal-body p-0 position-relative" style="min-height: 75vh;">
                <button type="button" class="close position-absolute text-white" data-dismiss="modal"
                        style="top: 15px; right: 20px; z-index: 10; opacity: 1; font-size: 2rem; text-shadow: 0 0 10px rgba(0,0,0,0.8);">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="position-absolute text-white px-3 py-2" id="galleryServiceName"
                     style="top: 15px; left: 20px; z-index: 10; font-weight: 600; text-shadow: 0 0 10px rgba(0,0,0,0.8);"></div>
                <div id="galleryContainer" style="width: 100%; height: 75vh; overflow: hidden; position: relative;">
                    <div id="galleryTrack" style="display: flex; height: 100%; transition: transform 0.3s ease;"></div>
                </div>
                <button class="gallery-nav gallery-prev" id="galleryPrev" style="display:none;"><i class="fa fa-chevron-left"></i></button>
                <button class="gallery-nav gallery-next" id="galleryNext" style="display:none;"><i class="fa fa-chevron-right"></i></button>
                <div class="position-absolute text-white" id="galleryCounter"
                     style="bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 10;
                            background: rgba(0,0,0,0.6); padding: 4px 14px; border-radius: 20px; font-size: 0.85rem;"></div>
            </div>
        </div>
    </div>
</div>

<style>
.service-card-public { border-radius: 12px; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s; }
.service-card-public:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.12) !important; }
.service-card-public:hover .service-photo-clickable { transform: scale(1.05); }
.gallery-nav {
    position: absolute; top: 50%; transform: translateY(-50%); z-index: 10;
    background: rgba(255,255,255,0.2); border: none; color: #fff;
    width: 44px; height: 44px; border-radius: 50%; font-size: 1.1rem;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(4px);
}
.gallery-nav:hover { background: rgba(255,255,255,0.4); }
.gallery-prev { left: 12px; }
.gallery-next { right: 12px; }
.gallery-slide { min-width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.gallery-slide img { max-width: 100%; max-height: 100%; object-fit: contain; }
</style>

@push('scripts')
<script>
(function() {
    var idx = 0, photos = [];
    var track = document.getElementById('galleryTrack');
    var container = document.getElementById('galleryContainer');
    var counter = document.getElementById('galleryCounter');
    var prevBtn = document.getElementById('galleryPrev');
    var nextBtn = document.getElementById('galleryNext');

    function open(list, name) {
        photos = list; idx = 0;
        document.getElementById('galleryServiceName').textContent = name;
        track.innerHTML = '';
        photos.forEach(function(p, i) {
            var d = document.createElement('div'); d.className = 'gallery-slide';
            var img = document.createElement('img'); img.src = '/storage/' + p; img.draggable = false;
            d.appendChild(img); track.appendChild(d);
        });
        prevBtn.style.display = nextBtn.style.display = photos.length > 1 ? 'flex' : 'none';
        update(); $('#photoGalleryModal').modal('show');
    }
    function go(i) { idx = i < 0 ? photos.length - 1 : i >= photos.length ? 0 : i; update(); }
    function update() {
        track.style.transform = 'translateX(-' + (idx * 100) + '%)';
        counter.textContent = (idx + 1) + ' / ' + photos.length;
    }
    prevBtn.onclick = function() { go(idx - 1); };
    nextBtn.onclick = function() { go(idx + 1); };

    var startX = 0, dragging = false, dx = 0;
    container.addEventListener('touchstart', function(e) { startX = e.touches[0].clientX; dragging = true; track.style.transition = 'none'; }, {passive:true});
    container.addEventListener('touchmove', function(e) { if (!dragging) return; dx = e.touches[0].clientX - startX; track.style.transform = 'translateX(' + (-(idx*container.offsetWidth)+dx) + 'px)'; }, {passive:true});
    container.addEventListener('touchend', function() { if (!dragging) return; dragging = false; track.style.transition = 'transform 0.3s ease'; Math.abs(dx) > 50 ? go(dx < 0 ? idx+1 : idx-1) : update(); dx = 0; });

    $(document).on('click', '.service-photo-clickable', function() {
        open($(this).data('photos'), $(this).data('service-name'));
    });
})();
</script>
@endpush
@endsection
