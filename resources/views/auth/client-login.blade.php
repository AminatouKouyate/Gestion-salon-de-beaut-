{{--
    Vue : Page de connexion client
    Description : Formulaire de connexion pour les clients du salon avec email, mot de passe, option "se souvenir de moi" et liens vers l'inscription et la récupération de mot de passe.
--}}
@extends('auth.layout')

@section('title','Connexion - KAARJA Beauté')

@section('left')
    <div class="left-content">
        <div class="left-icon">
            <i class="fa fa-scissors"></i>
        </div>
        <h2>Bienvenue chez <span>KAARJA Beauté</span></h2>
        <div class="gold-line"></div>
        <p class="left-subtitle">Votre destination beauté d'exception.<br>Réservez vos soins, suivez vos rendez-vous et profitez d'une expérience personnalisée.</p>
        <ul class="left-features">
            <li><i class="fa fa-calendar"></i> Réservation en ligne 24h/24</li>
            <li><i class="fa fa-heart"></i> Soins personnalisés</li>
            <li><i class="fa fa-gift"></i> Offres exclusives membres</li>
            <li><i class="fa fa-star"></i> Professionnels certifiés</li>
        </ul>
    </div>
@endsection

@section('right')
    <div class="form-logo"><i class="fa fa-scissors"></i></div>
    <h3>Connexion</h3>
    <p class="form-tagline">Accédez à votre espace beauté personnel</p>

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

    @if ($errors->any())
        <div class="alert-luxury alert-danger" role="alert">
            <i class="fa fa-exclamation-circle" style="margin-top:2px;"></i>
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ url('/client/login') }}" autocomplete="off">
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
            <a href="{{ route('client.password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn-luxury">
            <i class="fa fa-sign-in"></i> Se connecter
        </button>
    </form>

    <div class="divider-luxury"><span>ou</span></div>

    <a href="{{ route('client.register') }}" class="btn-register-luxury">
        <i class="fa fa-user-plus"></i> Créer un compte
    </a>
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
