<div class="nk-sidebar">
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">

            {{-- Menu pour les clients authentifiés avec le guard 'clients' --}}
            @auth('clients')
                @include('partials.sidebar.client-menu')
            @endauth

            {{-- Menu pour les admins authentifiés avec le guard par défaut 'web' --}}
            @auth('web')
                @include('partials.sidebar.admin-menu')
            @endauth

            {{-- Menu pour les employés authentifiés avec le guard 'employees' --}}
            @auth('employees')
                @include('partials.sidebar.employee-menu')
            @endauth

            {{-- Séparateur et switch de thème --}}
            <li class="nav-label mt-4">Apparence</li>
            <li>
                <div class="theme-switch-wrapper">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="theme-label">
                            <i class="icon-bulb"></i>
                            <span id="theme-label-text">Mode Sombre</span>
                        </span>
                        <label class="theme-switch">
                            <input type="checkbox" id="theme-toggle">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Force l'affichage complet de la sidebar
        document.body.setAttribute('data-sidebar-style', 'full');
    });
</script>

