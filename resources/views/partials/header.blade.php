{{--
    Vue : En-tête de navigation (partial)
    Description : Composant d'en-tête avec la barre de navigation supérieure, recherche, notifications et menu utilisateur.
--}}
<nav class="beauty-navbar" role="navigation" aria-label="Main navigation">
    <div class="navbar-inner">
        <a href="{{ url('/') }}" class="beauty-brand">
            <img src="{{ asset('images/image1.jpg') }}" alt="Logo">
            <span class="beauty-brand-name">KAARJA Beauté</span>
        </a>

        <ul class="beauty-nav" role="menubar" aria-label="Primary">
            <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Accueil</a></li>
            <li><a href="{{ Route::has('client.appointments.index') ? route('client.appointments.index') : url('/client/appointments') }}"><i class="fa fa-calendar"></i> Rendez-vous</a></li>
            <li><a href="{{ Route::has('services.public') ? route('services.public') : url('/services') }}"><i class="fa fa-scissors"></i> Services</a></li>
        </ul>

        <div class="beauty-nav-right">
            <button class="mobile-toggle" aria-label="Ouvrir le menu mobile" onclick="document.querySelector('.mobile-menu').classList.toggle('show')"><i class="fa fa-bars"></i></button>

            @if(auth('clients')->check())
                @if(Route::has('client.notifications.index'))
                <div class="nav-dropdown">
                    <button class="nav-icon-btn" aria-haspopup="true" aria-expanded="false" onclick="toggleNavDropdown(this)"><i class="fa fa-bell"></i>
                        @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                            <span class="notif-badge">{{ $unreadNotificationsCount }}</span>
                        @endif
                    </button>
                    <div class="nav-dropdown-menu">
                        <div style="padding:10px 12px;font-weight:700">{{ $unreadNotificationsCount ?? 0 }} Nouvelle(s)</div>
                        <div style="max-height:260px;overflow:auto">
                            @if(isset($headerNotifications) && $headerNotifications->count())
                                @foreach($headerNotifications as $notification)
                                    <a href="{{ route('client.notifications.index') }}">
                                        <i class="fa fa-bell"></i>
                                        <div style="margin-left:8px">
                                            <div style="font-weight:700">{{ \Illuminate\Support\Str::limit($notification->title,40) }}</div>
                                            <small style="color:#6B7280">{{ \Illuminate\Support\Str::limit($notification->message,80) }}</small>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div style="padding:12px;color:#6B7280">Aucune notification</div>
                            @endif
                        </div>
                        <div style="padding:10px;text-align:right;border-top:1px solid #F3F4F6"><a href="{{ route('client.notifications.index') }}">Voir toutes</a></div>
                    </div>
                </div>
                @endif

                <div class="nav-dropdown">
                    <button class="nav-user-btn" onclick="toggleNavDropdown(this)" aria-haspopup="true" aria-expanded="false">
                        @if(auth('clients')->user()->photo)
                            <img src="{{ asset('storage/' . auth('clients')->user()->photo) }}" alt="Avatar">
                        @else
                            <div class="user-placeholder">{{ strtoupper(substr(auth('clients')->user()->name,0,1)) }}</div>
                        @endif
                        <span class="user-name">{{ auth('clients')->user()->name }}</span>
                    </button>
                    <div class="profile-dropdown">
                        <div class="profile-dropdown-header">
                            <h6>{{ auth('clients')->user()->name }}</h6>
                            <small>{{ auth('clients')->user()->email }}</small>
                        </div>
                        <a href="{{ route('client.profile') }}"><i class="fa fa-user"></i> Mon profil</a>
                        <button class="logout-btn" onclick="event.preventDefault(); document.getElementById('header-client-logout-form').submit();"><i class="fa fa-sign-out"></i> Déconnexion</button>
                    </div>
                </div>
                <form id="header-client-logout-form" action="{{ route('client.logout') }}" method="POST" style="display:none">@csrf</form>

            @elseif(auth('employees')->check())
                @if(Route::has('employee.notifications.index'))
                <div class="nav-dropdown">
                    <button class="nav-icon-btn" aria-haspopup="true" aria-expanded="false" onclick="toggleNavDropdown(this)"><i class="fa fa-bell"></i>
                        @if(isset($employeeUnreadCount) && $employeeUnreadCount > 0)
                            <span class="notif-badge">{{ $employeeUnreadCount }}</span>
                        @endif
                    </button>
                    <div class="nav-dropdown-menu">
                        <div style="padding:10px 12px;font-weight:700">{{ $employeeUnreadCount ?? 0 }} Nouvelle(s)</div>
                        <div style="max-height:260px;overflow:auto">
                            @if(isset($employeeNotifications) && $employeeNotifications->count())
                                @foreach($employeeNotifications as $notification)
                                    <a href="{{ route('employee.notifications.index') }}">
                                        <i class="fa fa-bell"></i>
                                        <div style="margin-left:8px">
                                            <div style="font-weight:700">{{ \Illuminate\Support\Str::limit($notification->title,40) }}</div>
                                            <small style="color:#6B7280">{{ \Illuminate\Support\Str::limit($notification->message,80) }}</small>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div style="padding:12px;color:#6B7280">Aucune notification</div>
                            @endif
                        </div>
                        <div style="padding:10px;text-align:right;border-top:1px solid #F3F4F6"><a href="{{ route('employee.notifications.index') }}">Voir toutes</a></div>
                    </div>
                </div>
                @endif

                <div class="nav-dropdown">
                    <button class="nav-user-btn" onclick="toggleNavDropdown(this)">
                        @if(auth('employees')->user()->photo)
                            <img src="{{ asset('storage/' . auth('employees')->user()->photo) }}" alt="Avatar">
                        @else
                            <div class="user-placeholder">{{ strtoupper(substr(auth('employees')->user()->name,0,1)) }}</div>
                        @endif
                        <span class="user-name">{{ auth('employees')->user()->name }}</span>
                    </button>
                    <div class="profile-dropdown">
                        <div class="profile-dropdown-header">
                            <h6>{{ auth('employees')->user()->name }}</h6>
                            <small>{{ auth('employees')->user()->email }}</small>
                        </div>
                        <a href="{{ route('employee.profile') }}"><i class="fa fa-user"></i> Mon profil</a>
                        <button class="logout-btn" onclick="event.preventDefault(); document.getElementById('header-employee-logout-form').submit();"><i class="fa fa-sign-out"></i> Déconnexion</button>
                    </div>
                </div>
                <form id="header-employee-logout-form" action="{{ route('employee.logout') }}" method="POST" style="display:none">@csrf</form>

            @elseif(auth('web')->check())
                <div class="nav-dropdown">
                    <button class="nav-icon-btn" onclick="toggleNavDropdown(this)"><i class="fa fa-bell"></i></button>
                    <div class="nav-dropdown-menu"><div style="padding:12px;color:#6B7280">Aucune notification</div></div>
                </div>

                <div class="nav-dropdown">
                    <button class="nav-user-btn" onclick="toggleNavDropdown(this)">
                        @if(auth('web')->user()->photo)
                            <img src="{{ asset('storage/' . auth('web')->user()->photo) }}" alt="Avatar">
                        @else
                            <div class="user-placeholder">{{ strtoupper(substr(auth('web')->user()->name ?? 'A',0,1)) }}</div>
                        @endif
                        <span class="user-name">{{ auth('web')->user()->name ?? 'Admin' }}</span>
                    </button>
                    <div class="profile-dropdown">
                        <div class="profile-dropdown-header">
                            <h6>{{ auth('web')->user()->name ?? 'Admin' }}</h6>
                            <small>{{ auth('web')->user()->email ?? '' }}</small>
                        </div>
                        <a href="#"><i class="fa fa-user"></i> Mon profil</a>
                        <button class="logout-btn" onclick="event.preventDefault(); document.getElementById('header-admin-logout-form').submit();"><i class="fa fa-sign-out"></i> Déconnexion</button>
                    </div>
                </div>
                <form id="header-admin-logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
            @else
                <a href="{{ route('login') }}" class="btn btn-theme">Se connecter</a>
            @endif

        </div>
    </div>
</nav>

@push('scripts')
<script>
// Toggle a nav dropdown (opens the clicked one, closes others)
function toggleNavDropdown(btn){
    try{
        var wrapper = btn.closest('.nav-dropdown');
        if(!wrapper) return;
        var wasOpen = wrapper.classList.contains('open');
        // close others
        document.querySelectorAll('.nav-dropdown.open').forEach(function(d){ if(d!==wrapper) { d.classList.remove('open'); var b = d.querySelector('button'); if(b) b.setAttribute('aria-expanded','false'); }});
        if(wasOpen){ wrapper.classList.remove('open'); btn.setAttribute('aria-expanded','false'); }
        else { wrapper.classList.add('open'); btn.setAttribute('aria-expanded','true'); }
    }catch(err){ console.error('toggleNavDropdown error',err); }
}
// expose to global in case other scripts call it or override scopes
window.toggleNavDropdown = toggleNavDropdown;

// Close open nav dropdowns when clicking outside
document.addEventListener('click', function(e){
    if(!e.target.closest('.nav-dropdown')){
        document.querySelectorAll('.nav-dropdown.open').forEach(function(d){ d.classList.remove('open'); var b = d.querySelector('button'); if(b) b.setAttribute('aria-expanded','false'); });
    }
});

// Prevent clicks inside dropdown menu from closing it via document handler
document.addEventListener('click', function(e){
    var menu = e.target.closest('.nav-dropdown-menu, .profile-dropdown');
    if(menu) e.stopPropagation();
}, true);
</script>
@endpush
