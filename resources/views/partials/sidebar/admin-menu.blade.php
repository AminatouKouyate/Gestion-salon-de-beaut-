<li class="nav-label">Administration</li>

<li>
    <a href="{{ route('admin.clients.index') }}">
        <i class="icon-speedometer"></i>
        <span>Dashboard</span>
    </a>
</li>

<li>
    <a href="{{ route('admin.clients.index') }}">
        <i class="icon-user"></i>
        <span>Clients</span>
    </a>
</li>

<li>
    <a href="{{ route('admin.services.index') }}">
        <i class="icon-layers"></i>
        <span>Services</span>
    </a>
</li>

<li>
    <a href="{{ route('admin.appointments.index') }}">
        <i class="icon-calendar"></i>
        <span>Rendez-vous</span>
    </a>
</li>

<li>
    <a href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
        <i class="icon-logout"></i>
        <span>Déconnexion</span>
    </a>
    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>





<nav class="sidebar">
    <ul class="list-unstyled components">
        <li>
            <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
        </li>
        <li>
            <a href="{{ route('admin.employees.index') }}">Employés</a>
        </li>
        <li>
            <a href="{{ route('admin.clients.index') }}">Clients</a>
        </li>
        <li>
            <a href="{{ route('admin.services.index') }}">Services</a>
        </li>
        <li>
            <a href="{{ route('admin.appointments.index') }}">Rendez-vous</a>
        </li>
        <li>
            <a href="{{ route('admin.payments.index') }}">Paiements</a>
        </li>
        <li>
            <a href="{{ route('admin.stocks.index') }}">Stock</a>
        </li>
        <li>
            <a href="{{ route('admin.reports.index') }}">Rapports</a>
        </li>
    </ul>
</nav>
