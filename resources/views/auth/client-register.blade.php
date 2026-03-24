{{--
    Vue : Page d'inscription client
    Description : Formulaire d'inscription pour les nouveaux clients avec nom, email, téléphone, mot de passe et confirmation.
--}}
@extends('auth.layout')

@section('title','Inscription - KAARJA Beauté')

@section('left')
    <div class="left-content">
        <div class="left-icon"></div>
        <h2>Rejoignez <span>KAARJA Beauté</span></h2>
        <div class="gold-line"></div>
        <p class="left-subtitle">Créez votre compte et accédez à nos services exclusifs, programme fidélité et réservation en ligne.</p>
        <ul class="left-features">
            <li><i class="fa fa-calendar"></i> Réservation en ligne 24h/24</li>
            <li><i class="fa fa-heart"></i> Programme de fidélité</li>
            <li><i class="fa fa-gift"></i> Offres exclusives membres</li>
            <li><i class="fa fa-star"></i> Suivi personnalisé</li>
        </ul>
    </div>
@endsection

@section('right')
    <div class="form-logo"><i class="fa fa-user-plus"></i></div>
    <h3>Créer un Compte</h3>
    <p class="form-tagline">Remplissez vos informations pour commencer</p>

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

    @if(session('success'))
        <div class="alert-luxury alert-success" role="alert">
            <i class="fa fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ url('/client/register') }}" autocomplete="off">
        @csrf

        <div class="form-group-luxury">
            <label for="name"><i class="fa fa-user"></i> Nom complet</label>
            <input type="text" class="input-luxury @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Votre nom complet" required autofocus>
        </div>

        <div class="form-group-luxury">
            <label for="email"><i class="fa fa-envelope"></i> Email</label>
            <input type="email" class="input-luxury @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com" required>
        </div>

        <div class="form-group-luxury">
            <label for="phone"><i class="fa fa-phone"></i> Téléphone</label>
            <input type="tel" class="input-luxury @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+223 XX XX XX XX">
        </div>

        <div class="form-group-luxury">
            <label for="password"><i class="fa fa-lock"></i> Mot de passe</label>
            <div class="password-wrapper">
                <input type="password" class="input-luxury @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
                <button type="button" class="password-toggle" onclick="togglePasswordVisibility('password','password-eye')" aria-label="Afficher le mot de passe">
                    <i class="fa fa-eye" id="password-eye"></i>
                </button>
            </div>
        </div>

        <div class="form-group-luxury">
            <label for="password_confirmation"><i class="fa fa-lock"></i> Confirmer le mot de passe</label>
            <div class="password-wrapper">
                <input type="password" class="input-luxury" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                <button type="button" class="password-toggle" onclick="togglePasswordVisibility('password_confirmation','confirm-eye')" aria-label="Afficher le mot de passe">
                    <i class="fa fa-eye" id="confirm-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-luxury">
            <i class="fa fa-user-plus"></i> Créer mon Compte
        </button>
    </form>

    <div class="divider-luxury"><span>ou</span></div>

    <a href="{{ route('client.login') }}" class="btn-register-luxury">
        <i class="fa fa-sign-in"></i> J'ai déjà un compte
    </a>
    <a href="{{ route('home') }}" class="back-link"><i class="fa fa-arrow-left"></i> Retour à l'accueil</a>
@endsection

@push('scripts')
<script>
function togglePasswordVisibility(inputId, eyeId) {
    var input = document.getElementById(inputId);
    var eye = document.getElementById(eyeId);
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
