{{--
    Vue : Menu sidebar - Client (partial)
    Description : Liens de navigation de la sidebar pour l'espace client : tableau de bord, rendez-vous, services, paiements, profil, chatbot.
--}}
<li class="nav-label">Espace Client</li>

<li>
    <a href="{{ route('client.dashboard') }}">
        <i class="icon-home"></i>
        <span>Dashboard</span>
    </a>
</li>

<li class="nav-label">Services & RDV</li>

<li>
    <a href="{{ route('client.services') }}">
        <i class="icon-list"></i>
        <span>Nos services</span>
    </a>
</li>

<li>
    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
        <i class="icon-calendar"></i>
        <span>Rendez-vous</span>
    </a>
    <ul aria-expanded="false">
        <li><a href="{{ route('client.appointments.create') }}"><i class="fa fa-plus-circle mr-2"></i>Prendre RDV</a></li>
        <li><a href="{{ route('client.appointments.index') }}"><i class="fa fa-list mr-2"></i>Mes RDV à venir</a></li>
        <li><a href="{{ route('client.appointments.history') }}"><i class="fa fa-history mr-2"></i>Historique</a></li>
        <li><a href="{{ route('client.appointments.calendar') }}"><i class="fa fa-calendar mr-2"></i>Calendrier</a></li>
    </ul>
</li>

<li class="nav-label">Paiements</li>

<li>
    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
        <i class="icon-wallet"></i>
        <span>Paiements</span>
    </a>
    <ul aria-expanded="false">
        <li><a href="{{ route('client.payments.index') }}"><i class="fa fa-list mr-2"></i>Mes paiements</a></li>
        <li><a href="{{ route('client.payments.create') }}"><i class="fa fa-credit-card mr-2"></i>Effectuer un paiement</a></li>
    </ul>
</li>



<li class="nav-label">Mon Compte</li>

<!--
<li>
    <a href="{{ route('client.notifications.index') }}">
        <i class="icon-bell"></i>
        <span>Notifications</span>
        @php
            $unreadCount = Auth::guard('clients')->user()->unreadNotifications()->count();
        @endphp

        @if($unreadCount > 0)
            <span class="badge badge-danger float-right mt-1">{{ $unreadCount }}</span>
        @endif
    </a>
</li>
-->



