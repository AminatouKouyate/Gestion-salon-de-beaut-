

        <!-- Label -->
        <li class="nav-label text-uppercase text-muted mb-3">
            Administration
        </li>

        <!-- Dashboard -->
        <li class="mb-2">
            <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-speedometer2 me-2"></i>
                <span>Tableau de bord</span>
            </a>
        </li>

        <!-- Employés -->
        <li class="mb-2">
            <a href="{{ route('admin.employees.index') }}" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-people me-2"></i>
                <span>Employés</span>
            </a>
        </li>

        <!-- Clients -->
        <li class="mb-2">
            <a href="{{ route('admin.clients.index') }}" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-person me-2"></i>
                <span>Clients</span>
            </a>
        </li>

        <!-- Services -->
        <li class="mb-2">
            <a href="{{ route('admin.services.index') }}" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-layers me-2"></i>
                <span>Services</span>
            </a>
        </li>

        <!-- Rendez-vous -->
        <li class="mb-2">
            <a href="{{ route('admin.appointments.index') }}" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-calendar-check me-2"></i>
                <span>Rendez-vous</span>
            </a>
        </li>

        <!-- Paiements -->
        <li class="mb-2">
            <a href="{{ route('admin.payments.index') }}" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-credit-card me-2"></i>
                <span>Paiements</span>
            </a>
        </li>

        <!-- Stock -->
        <li class="mb-2">
            <a href="{{ route('admin.stocks.index') }}" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-basket me-2"></i>
                <span>Stock</span>
            </a>
        </li>

        <!-- Rapports -->
        <li class="mb-2">
            <a href="{{ route('admin.reports.index') }}" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-bar-chart-line me-2"></i>
                <span>Rapports</span>
            </a>
        </li>


<!-- Bootstrap Icons CDN (à inclure dans ton master layout si pas déjà fait) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
