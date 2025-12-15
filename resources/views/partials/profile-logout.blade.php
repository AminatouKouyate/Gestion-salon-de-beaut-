<li>
    <a href="{{ route($profile_route) }}">
        <i class="icon-user menu-icon"></i><span class="nav-text">Mon Profil</span>
    </a>
</li>
<li>
    <a href="#" onclick="event.preventDefault(); document.getElementById('{{ $form_id }}').submit();">
        <i class="icon-logout menu-icon"></i><span class="nav-text">Déconnexion</span>
    </a>
    <form id="{{ $form_id }}" action="{{ route($logout_route) }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>
