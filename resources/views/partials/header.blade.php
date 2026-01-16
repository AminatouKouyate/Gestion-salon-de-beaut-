<div class="header">
    <div class="header-content clearfix">

        <div class="nav-control">
            <div class="hamburger">
                <span class="toggle-icon"><i class="icon-menu"></i></span>
            </div>
        </div>
        <div class="header-left">
            <div class="input-group icons">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent border-0 pr-2 pr-sm-3" id="basic-addon1"><i class="mdi mdi-magnify"></i></span>
                </div>
                <input type="search" class="form-control" placeholder="Search Dashboard" aria-label="Search Dashboard">
                <div class="drop-down animated flipInX d-md-none">
                    <form action="#">
                        <input type="text" class="form-control" placeholder="Search">
                    </form>
                </div>
            </div>
        </div>
        <div class="header-right">
            <ul class="clearfix">
                {{-- Theme Toggle Button --}}
                <li class="icons">
                    <button type="button" id="theme-toggle-btn" class="theme-toggle-btn" title="Changer le thème">
                        <i class="fa fa-sun-o icon-sun"></i>
                        <i class="fa fa-moon-o icon-moon"></i>
                    </button>
                </li>

                @auth('clients')
                <li class="icons dropdown">
                    <a href="javascript:void(0)" data-toggle="dropdown">
                        <i class="mdi mdi-bell-outline"></i>
                        @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                            <span class="badge badge-pill badge-danger">{{ $unreadNotificationsCount }}</span>
                        @endif
                    </a>
                    <div class="drop-down animated fadeIn dropdown-menu dropdown-notfication">
                        <div class="dropdown-content-heading d-flex justify-content-between">
                            <span class="text">{{ $unreadNotificationsCount ?? 0 }} Nouvelles</span>
                        </div>
                        <div class="dropdown-content-body">
                            <ul>
                                @if(isset($headerNotifications) && $headerNotifications->count() > 0)
                                    @foreach($headerNotifications as $notification)
                                    <li>
                                        <a href="{{ route('client.notifications.index') }}">
                                            <span class="mr-3 avatar-icon bg-success-lighten-2">
                                                <i class="icon-bell"></i>
                                            </span>
                                            <div class="notification-content">
                                                <h6 class="notification-heading">{{ \Illuminate\Support\Str::limit($notification->title, 20) }}</h6>
                                                <span class="notification-text">{{ \Illuminate\Support\Str::limit($notification->message, 30) }}</span>
                                                <small class="notification-timestamp">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </a>
                                    </li>
                                    @endforeach
                                @else
                                    <li class="text-center p-3">Aucune notification</li>
                                @endif
                                <li>
                                    <a href="{{ route('client.notifications.index') }}" class="d-flex justify-content-between">
                                        <span>Voir toutes les notifications</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="icons dropdown">
                    <div class="user-img c-pointer position-relative"   data-toggle="dropdown">
                        <span class="activity active"></span>
                        <img src="{{ asset('images/user/1.png') }}" height="40" width="40" alt="">
                    </div>
                    <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                        <div class="dropdown-content-body">
                            <ul>
                                <li>
                                    <a href="{{ route('client.profile') }}"><i class="icon-user"></i> <span>Mon Profil</span></a>
                                </li>
                                <hr class="my-2">
                                <li>
                                    <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="icon-key"></i> <span>Déconnexion</span></a>
                                    <form id="logout-form" action="{{ route('client.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                @endauth

                @auth('employees')
                <li class="icons dropdown">
                    <a href="{{ route('employee.notifications.index') }}">
                        <i class="mdi mdi-bell-outline"></i>
                        @if(auth('employees')->check() && auth('employees')->user()->unreadNotificationsCount() > 0)
                            <span class="badge badge-pill badge-danger">{{ auth('employees')->user()->unreadNotificationsCount() }}</span>
                        @endif
                    </a>
                </li>
                <li class="icons dropdown">
                    <div class="user-img c-pointer position-relative"   data-toggle="dropdown">
                        <span class="activity active"></span>
                        <img src="{{ asset('images/user/1.png') }}" height="40" width="40" alt="">
                    </div>
                    <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                        <div class="dropdown-content-body">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('employee-logout-form').submit();"><i class="icon-key"></i> <span>Déconnexion</span></a>
                                    <form id="employee-logout-form" action="{{ route('employee.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                @endauth

                @auth('web')
                <li class="icons dropdown">
                    <a href="{{ route('admin.notifications.index') }}">
                        <i class="mdi mdi-bell-outline"></i>
                    </a>
                </li>
                <li class="icons dropdown">
                    <div class="user-img c-pointer position-relative"   data-toggle="dropdown">
                        <span class="activity active"></span>
                        <img src="{{ asset('images/user/1.png') }}" height="40" width="40" alt="">
                    </div>
                    <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                        <div class="dropdown-content-body">
                            <ul>
                                <li>
                                    <a href="{{ route('admin.profile') }}"><i class="icon-user"></i> <span>Profil</span></a>
                                </li>
                                <hr class="my-2">
                                <li>
                                    <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();"><i class="icon-key"></i> <span>Déconnexion</span></a>
                                    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                @endauth

            </ul>
        </div>
    </div>
</div>

<!-- Bouton Flottant IA style WhatsApp/Meta AI -->
<a href="{{ route('client.chatbot.index') }}" class="ai-floating-btn" title="Assistant IA">
    <i class="fa fa-comments"></i>
</a>

<style>
.ai-floating-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #00C6FF, #0072FF);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    z-index: 9999;
    transition: all 0.3s ease;
    border: 2px solid white;
}
.ai-floating-btn:hover {
    transform: scale(1.1) rotate(10deg);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}
.ai-floating-btn i {
    color: white;
    font-size: 28px;
}
</style>