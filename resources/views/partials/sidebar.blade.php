{{--
    Vue : Barre latérale de navigation (partial)
    Description : Composant de la barre latérale principale avec le menu de navigation adapté au rôle de l'utilisateur.
--}}
<div class="nk-sidebar">
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">

            {{-- Menu EXCLUSIF selon le guard actif - UN SEUL menu affiché --}}
            @if(auth('clients')->check())
                {{-- Menu Client --}}
                @include('partials.sidebar.client-menu')
            @elseif(auth('employees')->check())
                {{-- Menu Employé --}}
                @include('partials.sidebar.employee-menu')
            @elseif(auth('web')->check())
                {{-- Menu Admin --}}
                @include('partials.sidebar.admin-menu')
            @endif



        </ul>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.body.setAttribute('data-sidebar-style', 'full');
    });
</script>

<style>
/* Sidebar theming to match client design */
.nk-sidebar { background: transparent; }
.nk-sidebar .metismenu li a { color: #333; display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:8px; }
.nk-sidebar .metismenu li a .menu-icon { color: var(--primary); font-size:18px; }
.nk-sidebar .metismenu li.active > a, .nk-sidebar .metismenu li > a:hover { background: var(--primary-soft); color: var(--primary); }
.nk-sidebar .metismenu li.active > a .menu-icon, .nk-sidebar .metismenu li > a:hover .menu-icon { color: var(--primary); }
.nk-sidebar .metismenu li .badge { background: var(--accent); color: #fff; }
.nav-label { color: #8E8E8E; font-weight:700; }
.theme-switch .slider { background: var(--primary-light); }
</style>
