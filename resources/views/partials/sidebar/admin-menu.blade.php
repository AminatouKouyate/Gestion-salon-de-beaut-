{{--
    Vue : Menu sidebar - Administration (partial)
    Description : Liens de navigation de la sidebar pour l'espace administrateur : tableau de bord, clients, employés, services, rendez-vous, paiements, paramètres.
--}}
        <!-- Label -->
        <li class="nav-label">Administration</li>

        <!-- Dashboard -->
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="icon-speedometer menu-icon"></i>
                <span class="nav-text">Tableau de bord</span>
            </a>
        </li>

        <!-- Employés -->
        <li>
            <a href="{{ route('admin.employees.index') }}">
                <i class="icon-people menu-icon"></i>
                <span class="nav-text">Employés</span>
            </a>
        </li>

        <!-- Clients -->
        <li>
            <a href="{{ route('admin.clients.index') }}">
                <i class="icon-user menu-icon"></i>
                <span class="nav-text">Clients</span>
            </a>
        </li>

        <!-- Services -->
        <li>
            <a href="{{ route('admin.services.index') }}">
                <i class="icon-layers menu-icon"></i>
                <span class="nav-text">Services</span>
            </a>
        </li>

        <!-- Rendez-vous -->
        <li>
            <a href="{{ route('admin.appointments.index') }}">
                <i class="icon-calendar menu-icon"></i>
                <span class="nav-text">Rendez-vous</span>
            </a>
        </li>

        <!-- Planning -->
        <li class="has-submenu">
            <a href="javascript:void(0);">
                <i class="fa fa-calendar menu-icon"></i>
                <span class="nav-text">Planning</span>
                <i class="fa fa-angle-right pull-right"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.schedules.index') }}">
                        <i class="fa fa-calendar menu-icon"></i>
                        <span class="nav-text">Planning général</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.employees.index') }}">
                        <i class="fa fa-clock-o menu-icon"></i>
                        <span class="nav-text">Horaires employés</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Paiements -->
        <li>
            <a href="{{ route('admin.payments.index') }}">
                <i class="icon-credit-card menu-icon"></i>
                <span class="nav-text">Paiements</span>
            </a>
        </li>

        <!-- Stock -->
        <li>
            <a href="{{ route('admin.stocks.index') }}">
                <i class="icon-basket-loaded menu-icon"></i>
                <span class="nav-text">Stock</span>
            </a>
        </li>

        <!-- Demandes de congé -->
        <li>
            <a href="{{ route('admin.leaves.index') }}">
                <i class="icon-notebook menu-icon"></i>
                <span class="nav-text">Congés</span>
                @php
                    $pendingLeavesCount = \App\Models\LeaveRequest::pending()->count();
                @endphp
                @if($pendingLeavesCount > 0)
                    <span class="badge badge-warning float-right">{{ $pendingLeavesCount }}</span>
                @endif
            </a>
        </li>

        <!-- Messages employés -->
        <li>
            <a href="{{ route('admin.employee-messages.index') }}">
                <i class="icon-envelope menu-icon"></i>
                <span class="nav-text">Messages</span>
                @php
                    $pendingMessagesCount = \App\Models\EmployeeMessage::pending()->count();
                @endphp
                @if($pendingMessagesCount > 0)
                    <span class="badge badge-warning float-right">{{ $pendingMessagesCount }}</span>
                @endif
            </a>
        </li>

        <!-- Rapports -->
        <li>
            <a href="{{ route('admin.reports.index') }}">
                <i class="icon-graph menu-icon"></i>
                <span class="nav-text">Rapports</span>
            </a>
        </li>
