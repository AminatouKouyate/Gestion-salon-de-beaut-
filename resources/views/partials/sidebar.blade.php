<div class="nk-sidebar">
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">

            {{-- Menu pour les clients authentifiés avec le guard 'clients' --}}
            @auth('clients')
                @include('partials.sidebar.client-menu')
            @endauth

            {{-- Menu pour les admins authentifiés avec le guard par défaut 'web' --}}
            @auth('web')
                @include('partials.sidebar.admin-menu')
            @endauth

            {{-- Menu pour les employés authentifiés avec le guard 'employees' --}}
            @auth('employees')
                @include('partials.sidebar.employee-menu')
            @endauth

        </ul>
    </div>
</div>
