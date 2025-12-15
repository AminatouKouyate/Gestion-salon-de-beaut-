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

<li>
    <a href="{{ route('client.profile') }}">
        <i class="icon-user"></i>
        <span>Mon Profil</span>
    </a>
</li>

<li>
    <a href="{{ route('client.chatbot.index') }}">
        <i class="icon-bubbles"></i>
        <span>Assistant Chatbot</span>
    </a>
</li>

<li>
    <a href="{{ route('client.notifications.index') }}">
        <i class="icon-bell"></i>
        <span>Notifications</span>
    </a>
</li>

<li>
    <a href="#" onclick="event.preventDefault(); document.getElementById('client-logout-form').submit();">
        <i class="icon-logout"></i>
        <span>Déconnexion</span>
    </a>
    <form id="client-logout-form" action="{{ route('client.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>
