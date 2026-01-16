<li class="nav-label">Employé</li>

<li>
    <a href="{{ route('employee.dashboard') }}">
        <i class="icon-speedometer"></i>
        <span>Dashboard</span>
    </a>
</li>

<li>
    <a href="{{ route('employee.appointments.index') }}">
        <i class="icon-calendar"></i>
        <span>Mes rendez-vous</span>
    </a>
</li>

<li>
    <a href="{{ route('employee.services.index') }}">
        <i class="icon-layers"></i>
        <span>Services</span>
    </a>
</li>

<li>
    <a href="{{ route('employee.leaves.index') }}">
        <i class="icon-plane"></i>
        <span>Mes congés</span>
    </a>
</li>

<li>
    <a href="{{ route('employee.notifications.index') }}">
        <i class="icon-bell"></i>
        <span>Notifications</span>
        @if(auth('employees')->check() && auth('employees')->user()->unreadNotificationsCount() > 0)
            <span class="badge badge-danger">{{ auth('employees')->user()->unreadNotificationsCount() }}</span>
        @endif
    </a>
</li>

<li>
    <a href="#" onclick="event.preventDefault(); document.getElementById('employee-logout-form').submit();">
        <i class="icon-logout"></i>
        <span>Déconnexion</span>
    </a>
    <form id="employee-logout-form" action="{{ route('employee.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>
