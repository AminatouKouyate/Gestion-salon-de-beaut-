{{--
    Vue : Layout des pages publiques
    Description : Template de base pour les pages accessibles sans connexion : barre de navigation publique, alertes, pied de page avec informations du salon et scripts globaux.
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'KAARJA Beauté - Salon de Beauté')</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"></noscript>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">

    <script>
        (function(){
            @if($globalDarkMode ?? false)
                document.documentElement.classList.add('dark-theme');
            @endif
        })();
    </script>

    <style>
        body { padding-top: 80px; }
    </style>

    @stack('styles')
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

{{-- ===== NAVBAR (scrolled style by default) ===== --}}
<nav class="welcome-nav scrolled" id="welcomeNav">
    <div class="container">
        <a href="{{ route('home') }}" class="nav-brand">
            <span class="nav-brand-icon"></span>
            <span class="nav-brand-text">KAARJA Beauté</span>
        </a>

        <ul class="nav-links">
            <li><a href="{{ route('services.public') }}">Nos Services</a></li>
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

        <button class="mobile-nav-toggle" onclick="document.querySelector('.nav-links').classList.toggle('show-mobile');">
            <i class="fa fa-bars"></i>
        </button>
    </div>
</nav>

{{-- ===== CONTENT ===== --}}
@yield('content')

{{-- ===== FOOTER ===== --}}
<footer class="welcome-footer">
    <div class="container">
        <div class="footer-brand">KAARJA Beauté</div>
        <p><i class="fa fa-map-marker mr-2"></i>Bamako, Mali</p>
        <p><i class="fa fa-phone mr-2"></i>+223 70 76 05 12</p>
        <p><i class="fa fa-clock-o mr-2"></i>Lun - Sam : 9h - 18h</p>
        <div class="social-links">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-instagram"></i></a>
            <a href="#"><i class="fa fa-whatsapp"></i></a>
        </div>
        <p class="mt-4" style="font-size:12px;opacity:0.5;">&copy; {{ date('Y') }} KAARJA Beauté. Tous droits réservés.</p>
    </div>
</footer>

{{-- ===== SCRIPTS ===== --}}
<script src="{{ asset('plugins/common/common.min.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>

<script>
// Scroll reveal
function revealOnScroll() {
    var reveals = document.querySelectorAll('.reveal');
    reveals.forEach(function(el) {
        var top = el.getBoundingClientRect().top;
        if (top < window.innerHeight - 80) el.classList.add('visible');
    });
}
window.addEventListener('scroll', revealOnScroll);
window.addEventListener('load', revealOnScroll);

// Auto-hide alerts
var alertEl = document.getElementById('welcomeAlert');
if (alertEl) setTimeout(function(){ alertEl.style.opacity='0'; alertEl.style.transform='translateX(-50%) translateY(-20px)'; setTimeout(function(){ alertEl.remove(); },500); }, 5000);
</script>

@stack('scripts')

</body>
</html>
