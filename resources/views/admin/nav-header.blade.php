{{-- Vue partielle : En-tête de navigation (logo et nom du salon) --}}
{{-- Affiche le logo et le titre de l'application dans la barre de navigation latérale --}}
{{-- Utilise les routes : admin.dashboard ; les assets : images/logo.png, images/logo-compact.png --}}
<div class="nav-header">
    <div class="brand-logo">
        {{-- Lien vers le tableau de bord administrateur --}}
        <a href="{{ route('admin.dashboard') }}">
            {{-- Logo abrégé affiché quand la barre latérale est réduite --}}
            <b class="logo-abbr"><img src="{{ asset('images/logo.png') }}" alt=""> </b>
            {{-- Logo compact pour la version intermédiaire --}}
            <span class="logo-compact"><img src="{{ asset('images/logo-compact.png') }}" alt=""></span>
            {{-- Titre textuel du salon affiché quand la barre latérale est déployée --}}
            <span class="brand-title">
                <span class="text-white font-weight-bold" style="font-size: 1.2rem;">Gestion Salon</span>
            </span>
        </a>
    </div>
</div>
