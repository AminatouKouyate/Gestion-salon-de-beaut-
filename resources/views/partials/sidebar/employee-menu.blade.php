{{--
    Vue : Menu sidebar - Employé (partial)
    Description : Liens de navigation de la sidebar pour l'espace employé : tableau de bord, rendez-vous, planning, services, congés, messages, profil.
--}}
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
    <a href="{{ route('employee.schedules.index') }}">
        <i class="icon-clock"></i>
        <span>Mon Planning</span>
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
    <a href="{{ route('employee.messages.index') }}">
        <i class="icon-envelope"></i>
        <span>Messages Admin</span>
    </a>
</li>




