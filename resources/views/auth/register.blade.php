{{--
    Vue : Page d'inscription (générale)
    Description : Formulaire d'inscription générale avec nom, email, mot de passe et confirmation.
--}}
@extends('auth.layout')

@section('title','Inscription - KAARJA Beauté')

@section('left')
    <div class="left-content">
        <div class="form-logo"><i class="fa fa-user-plus"></i></div>
        <h2>Rejoignez KAARJA Beauté</h2>
        <div class="gold-line"></div>
        <p class="left-subtitle">Créez votre compte et accédez à nos services exclusifs.</p>
    </div>
@endsection

@section('right')
    @if ($errors->any())
        <div class="alert-luxury alert-danger" role="alert">
            <ul class="mb-0 pl-3" style="list-style:none;">
                @foreach ($errors->all() as $error)
                    <li><i class="fa fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" autocomplete="off">
        @csrf
        <div class="form-group">
            <label><i class="fa fa-user"></i> Nom complet</label>
            <input type="text" class="input-luxury @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Nom" required autofocus>
        </div>
        <div class="form-group">
            <label><i class="fa fa-envelope"></i> Email</label>
            <input type="email" class="input-luxury @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Adresse e-mail" required>
        </div>
        <div class="form-group">
            <label><i class="fa fa-lock"></i> Mot de passe</label>
            <input type="password" class="input-luxury @error('password') is-invalid @enderror" name="password" placeholder="Mot de passe" required>
        </div>
        <div class="form-group">
            <label><i class="fa fa-lock"></i> Confirmer</label>
            <input type="password" class="input-luxury" name="password_confirmation" placeholder="Confirmer le mot de passe" required>
        </div>

        <button class="btn-luxury" type="submit">S'inscrire</button>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="back-link">Vous avez déjà un compte ? Se connecter</a>
        </div>
    </form>
@endsection
