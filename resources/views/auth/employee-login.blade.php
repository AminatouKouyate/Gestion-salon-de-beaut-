{{--
    Vue : Page de connexion employé
    Description : Formulaire de connexion pour les employés du salon avec email, mot de passe et lien de récupération.
--}}
@extends('auth.layout')

@section('title','Connexion Employé - KAARJA Beauté')

@section('left')
    <div class="left-content">
        <div class="left-icon"><i class="fa fa-user-md"></i></div>
        <h2>Portail <span>Employé</span></h2>
        <div class="gold-line"></div>
        <p class="left-subtitle">Gérez vos rendez-vous, planning et communiquez avec l'administration.</p>
        <ul class="left-features">
            <li><i class="fa fa-calendar"></i> Gestion des rendez-vous</li>
            <li><i class="fa fa-clock-o"></i> Planning & horaires</li>
            <li><i class="fa fa-envelope"></i> Messagerie interne</li>
            <li><i class="fa fa-scissors"></i> Services assignés</li>
        </ul>
    </div>
@endsection

@section('right')
    <div class="form-logo"><i class="fa fa-user-md"></i></div>
    <h3>Connexion Employé</h3>
    <p class="form-tagline">Accédez à votre espace de travail</p>

    @if(session('error'))
        <div class="alert-luxury alert-danger" role="alert">
            <i class="fa fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="alert-luxury alert-success" role="alert">
            <i class="fa fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('status'))
        <div class="alert-luxury alert-success" role="alert">
            <i class="fa fa-check-circle"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-luxury alert-danger" role="alert">
            <i class="fa fa-exclamation-circle" style="margin-top:2px;"></i>
            <div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ url('/employee/login') }}" autocomplete="off">
        @csrf

        <div class="form-group-luxury">
            <label for="email"><i class="fa fa-envelope"></i> Adresse email</label>
            <input type="email" class="input-luxury @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com" required autofocus autocomplete="username">
        </div>

        <div class="form-group-luxury">
            <label for="password"><i class="fa fa-lock"></i> Mot de passe</label>
            <div class="password-wrapper">
                <input type="password" class="input-luxury @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                <button type="button" class="password-toggle" onclick="togglePasswordVisibility()" aria-label="Afficher le mot de passe">
                    <i class="fa fa-eye" id="password-eye"></i>
                </button>
            </div>
        </div>

        <div class="form-extras">
            <label class="custom-check-luxury">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <span>Se souvenir de moi</span>
            </label>
            <a href="{{ route('employee.password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn-luxury">
            <i class="fa fa-sign-in"></i> Se connecter
        </button>
    </form>

    <div class="divider-luxury"><span>ou</span></div>

    <a href="{{ route('home') }}" class="back-link"><i class="fa fa-arrow-left"></i> Retour à l'accueil</a>
@endsection

@push('scripts')
<script>
function togglePasswordVisibility() {
    const input = document.getElementById('password');
    const eye = document.getElementById('password-eye');
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}
</script>
@endpush
