{{--
    Vue : Page de connexion administrateur
    Description : Formulaire de connexion pour l'administrateur du salon avec email, mot de passe et lien de récupération.
--}}
@extends('auth.layout')

@section('title','Connexion Administrateur')

@section('left')
    <div class="left-content">
        <div class="left-icon"><i class="fa fa-shield"></i></div>
        <h2>Portail <span>Administrateur</span></h2>
        <div class="gold-line"></div>
        <p class="left-subtitle">Administration et gestion globale du salon.</p>
        <ul class="left-features">
            <li><i class="fa fa-users"></i> Gestion des utilisateurs</li>
            <li><i class="fa fa-cogs"></i> Paramètres et configurations</li>
            <li><i class="fa fa-line-chart"></i> Statistiques</li>
        </ul>
    </div>
@endsection

@section('right')
    <div class="form-logo"><i class="fa fa-user-secret"></i></div>
    <h3>Connexion Administrateur</h3>
    <p class="form-tagline">Accédez au panneau d'administration</p>

    @if(session('error'))
        <div class="alert-luxury alert-danger" role="alert">
            <i class="fa fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-luxury alert-danger" role="alert">
            <i class="fa fa-exclamation-circle"></i>
            <div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ url('/login') }}" autocomplete="off">
        @csrf
        <div class="form-group-luxury">
            <label for="email"><i class="fa fa-envelope"></i> Email</label>
            <input type="email" name="email" id="email" class="input-luxury" value="{{ old('email') }}" required autofocus autocomplete="username">
        </div>
        <div class="form-group-luxury">
            <label for="password"><i class="fa fa-lock"></i> Mot de passe</label>
            <input type="password" name="password" id="password" class="input-luxury" required autocomplete="current-password">
        </div>
        <div class="form-extras">
            <label class="custom-check-luxury">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <span>Se souvenir de moi</span>
            </label>
            <a href="{{ route('admin.password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
        </div>
        <button class="btn-luxury" type="submit"><i class="fa fa-sign-in"></i> Se connecter</button>
    </form>

    <div class="divider-luxury"><span>ou</span></div>
    <a href="{{ route('home') }}" class="back-link"><i class="fa fa-arrow-left"></i> Retour</a>
@endsection
