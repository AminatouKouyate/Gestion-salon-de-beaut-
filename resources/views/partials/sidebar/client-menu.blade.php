<li class="nav-label">Espace Client</li>

<li>
    <a href="{{ route('client.dashboard') }}">
        <i class="icon-home"></i>
        <span>Dashboard</span>
    </a>
</li>

<li>
    <a href="{{ route('client.appointments.index') }}">
        <i class="icon-calendar"></i>
        <span>Mes rendez-vous</span>
    </a>
</li>

<li>
    <a href="{{ route('client.appointments.history') }}">
        <i class="icon-clock"></i>
        <span>Historique RDV</span>
    </a>
</li>

<li>
    <a href="{{ route('client.services') }}">
        <i class="icon-list"></i>
        <span>Services</span>
    </a>
</li>

<li>
    <a href="{{ route('client.payments.index') }}">
        <i class="icon-wallet"></i>
        <span>Mes paiements</span>
    </a>
</li>


