{{--
    Vue : Page d'accueil publique du salon
    Description : Landing page du salon KAARJA Beauté avec présentation des services, témoignages, galerie et appels à l'action pour les différents espaces (client, employé, admin).
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>KAARJA Beauté - Salon de Beauté</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script>
        (function(){var t=localStorage.getItem('salon-theme');if(t==='dark')document.documentElement.classList.add('dark-theme');})();
    </script>

    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
</head>

<body>

{{-- Alerts --}}
@if(session('success'))
    <div class="welcome-alert welcome-alert-success" id="welcomeAlert">
        <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="welcome-alert welcome-alert-danger" id="welcomeAlert">
        <i class="fa fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
@endif


{{-- ===== NAVBAR ===== --}}
<nav class="welcome-nav" id="welcomeNav">
    <div class="container">
        <a href="{{ url('/') }}" class="nav-brand">
            <span class="nav-brand-icon"></span>
            <span class="nav-brand-text">KAARJA Beauté</span>
        </a>

        <ul class="nav-links">
            <li><a href="#services">Nos Services</a></li>
            <li><a href="#pourquoi">Pourquoi Nous</a></li>
            <li class="nav-login-dropdown">
                <a href="javascript:void(0)">Connexion <i class="fa fa-angle-down ml-1"></i></a>
                <div class="dropdown-menu-custom">
                    <a href="{{ route('client.login') }}">
                        <span class="dd-icon dd-client"><i class="fa fa-user"></i></span>
                        <div><strong>Espace Client</strong><br><small style="color:var(--text-muted);">Gérez vos rendez-vous</small></div>
                    </a>
                    <a href="{{ route('employee.login') }}">
                        <span class="dd-icon dd-employee"><i class="fa fa-briefcase"></i></span>
                        <div><strong>Espace Employé</strong><br><small style="color:var(--text-muted);">Planning & rendez-vous</small></div>
                    </a>
                    <a href="{{ route('login') }}">
                        <span class="dd-icon dd-admin"><i class="fa fa-lock"></i></span>
                        <div><strong>Administration</strong><br><small style="color:var(--text-muted);">Gestion du salon</small></div>
                    </a>
                </div>
            </li>
            <li><a href="{{ route('client.register') }}" class="btn-register-nav">S'inscrire</a></li>
        </ul>

        <button class="nav-theme-toggle" id="theme-toggle-welcome" onclick="toggleWelcomeTheme()" title="Changer le thème">
            <i class="fa fa-moon-o"></i>
        </button>

        <button class="mobile-nav-toggle" onclick="document.querySelector('.nav-links').classList.toggle('show-mobile');">
            <i class="fa fa-bars"></i>
        </button>
    </div>
</nav>

{{-- ===== HERO ===== --}}
<section class="hero">
    <div class="sparkle"></div><div class="sparkle"></div><div class="sparkle"></div>
    <div class="sparkle"></div><div class="sparkle"></div>
    <div class="hero-particle" id="heroParticles"></div>

    <div class="hero-content">
        <div class="hero-badge">Salon de Beauté Premium</div>
        <h1>Votre <span class="accent">Beauté</span>,<br>Notre Passion</h1>
        <p>Découvrez une expérience beauté unique chez KAARJA. Coiffure, soins, manucure — nos experts subliment votre beauté naturelle avec des produits premium.</p>
        <div class="hero-buttons">
            <a href="{{ route('client.login') }}" class="btn-hero-primary">
                <i class="fa fa-calendar mr-2"></i>Prendre Rendez-vous
            </a>
            <a href="#services" class="btn-hero-secondary">
                <i class="fa fa-scissors mr-2"></i>Découvrir nos Services
            </a>
        </div>
    </div>

    <a href="#services" class="hero-scroll"><i class="fa fa-angle-down"></i></a>
</section>

{{-- ===== SERVICES PREVIEW ===== --}}
<section class="section section-cream" id="services">
    <div class="container">
        <div class="section-title reveal">
            <span class="label">Nos Prestations</span>
            <h2>Des Services d'Exception</h2>
            <p>Découvrez notre gamme complète de soins et services beauté</p>
        </div>

        <div class="row reveal-stagger">
            <div class="col-lg-3 col-md-6 mb-4 reveal">
                <div class="service-preview-card">
                    <div class="card-icon">✂️</div>
                    <h5>Coiffure</h5>
                    <p>Coupes tendance, colorations, brushings et coiffures pour toutes les occasions</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 reveal">
                <div class="service-preview-card">
                    <div class="card-icon">💅</div>
                    <h5>Manucure & Pédicure</h5>
                    <p>Soins des ongles, pose de vernis, nail art et beauté des mains et pieds</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 reveal">
                <div class="service-preview-card">
                    <div class="card-icon">🌸</div>
                    <h5>Soins Visage</h5>
                    <p>Nettoyage de peau, masques, hydratation et traitements anti-âge</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 reveal">
                <div class="service-preview-card">
                    <div class="card-icon">💄</div>
                    <h5>Maquillage</h5>
                    <p>Maquillage professionnel pour mariages, événements et du quotidien</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 reveal">
            <a href="{{ route('services.public') }}" class="btn-hero-primary" style="background:linear-gradient(135deg, var(--primary), var(--dark));">
                <i class="fa fa-th-large mr-2"></i>Voir tous nos services
            </a>
        </div>
    </div>
</section>

{{-- ===== WHY US ===== --}}
<section class="section section-light" id="pourquoi">
    <div class="container">
        <div class="section-title reveal">
            <span class="label">Pourquoi Nous Choisir</span>
            <h2>L'Excellence au Quotidien</h2>
            <p>Ce qui fait de KAARJA Beauté un salon unique</p>
        </div>

        <div class="row reveal-stagger">
            <div class="col-lg-4 mb-4 reveal">
                <div class="why-card">
                    <div class="why-icon"><i class="fa fa-star"></i></div>
                    <h5>Expertise & Talent</h5>
                    <p>Notre équipe de professionnels qualifiés vous offre un service personnalisé d'exception</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4 reveal">
                <div class="why-card">
                    <div class="why-icon"><i class="fa fa-diamond"></i></div>
                    <h5>Produits Premium</h5>
                    <p>Nous utilisons exclusivement des produits haut de gamme pour des résultats exceptionnels</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4 reveal">
                <div class="why-card">
                    <div class="why-icon"><i class="fa fa-heart"></i></div>
                    <h5>Satisfaction Garantie</h5>
                    <p>Votre satisfaction est notre priorité. Programme fidélité et réductions exclusives</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="cta-section">
    <div class="container reveal">
        <h2>Prête pour une Transformation ?</h2>
        <p>Créez votre compte et réservez votre premier rendez-vous dès aujourd'hui</p>
        <a href="{{ route('client.register') }}" class="btn-cta">
            <i class="fa fa-user-plus mr-2"></i>Créer mon Compte Gratuit
        </a>
        <p class="mt-3" style="font-size:14px;opacity:0.7;">
            Déjà client ? <a href="{{ route('client.login') }}" style="color:var(--accent);font-weight:600;">Connectez-vous ici</a>
        </p>
    </div>
</section>

{{-- ===== FOOTER ===== --}}
<footer class="welcome-footer">
    <div class="container">
        <div class="footer-brand">KAARJA Beauté</div>
        <p><i class="fa fa-map-marker mr-2"></i>Bamako, Mali</p>
        <p><i class="fa fa-phone mr-2"></i>+223 XX XX XX XX</p>
        <p><i class="fa fa-clock-o mr-2"></i>Lun - Sam : 9h - 18h</p>
        <div class="social-links">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-instagram"></i></a>
            <a href="#"><i class="fa fa-whatsapp"></i></a>
        </div>
        <p class="mt-4" style="font-size:12px;opacity:0.5;">&copy; {{ date('Y') }} KAARJA Beauté. Tous droits réservés.</p>
    </div>
</footer>

<script>
// Navbar scroll effect
window.addEventListener('scroll', function(){
    var nav = document.getElementById('welcomeNav');
    if (window.scrollY > 50) nav.classList.add('scrolled');
    else nav.classList.remove('scrolled');
});

// Scroll reveal (supports .reveal, .reveal-left, .reveal-right, .reveal-scale)
function revealOnScroll() {
    var selectors = '.reveal, .reveal-left, .reveal-right, .reveal-scale';
    document.querySelectorAll(selectors).forEach(function(el) {
        var top = el.getBoundingClientRect().top;
        if (top < window.innerHeight - 60) el.classList.add('visible');
    });
}
window.addEventListener('scroll', revealOnScroll);
window.addEventListener('load', revealOnScroll);

// Floating particles in hero
(function() {
    var container = document.getElementById('heroParticles');
    if (!container) return;
    var count = 20;
    for (var i = 0; i < count; i++) {
        var p = document.createElement('span');
        p.className = 'floating-dot';
        p.style.left = Math.random() * 100 + '%';
        p.style.top = Math.random() * 100 + '%';
        p.style.animationDelay = (Math.random() * 6) + 's';
        p.style.animationDuration = (4 + Math.random() * 6) + 's';
        p.style.width = p.style.height = (2 + Math.random() * 4) + 'px';
        p.style.opacity = 0.2 + Math.random() * 0.4;
        container.appendChild(p);
    }
})();

// Counter animation for stats
function animateCounters() {
    document.querySelectorAll('[data-count]').forEach(function(el) {
        if (el.dataset.counted) return;
        var top = el.getBoundingClientRect().top;
        if (top > window.innerHeight) return;
        el.dataset.counted = '1';
        var target = parseInt(el.dataset.count);
        var duration = 1500;
        var start = 0;
        var startTime = null;
        function step(ts) {
            if (!startTime) startTime = ts;
            var progress = Math.min((ts - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target);
            if (progress < 1) requestAnimationFrame(step);
            else el.textContent = target;
        }
        requestAnimationFrame(step);
    });
}
window.addEventListener('scroll', animateCounters);
window.addEventListener('load', animateCounters);

// Theme toggle
var THEME_KEY = 'salon-theme';
function toggleWelcomeTheme() {
    var dark = document.body.classList.contains('dark-theme');
    if (dark) {
        document.body.classList.remove('dark-theme');
        document.documentElement.classList.remove('dark-theme');
        localStorage.setItem(THEME_KEY, 'light');
    } else {
        document.body.classList.add('dark-theme');
        document.documentElement.classList.add('dark-theme');
        localStorage.setItem(THEME_KEY, 'dark');
    }
}

// Auto-hide alerts
var alertEl = document.getElementById('welcomeAlert');
if (alertEl) setTimeout(function(){ alertEl.style.opacity='0'; alertEl.style.transform='translateX(-50%) translateY(-20px)'; setTimeout(function(){ alertEl.remove(); },500); }, 5000);
</script>

</body>
</html>
