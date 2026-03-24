{{--
    Vue : Mot de passe oublié - Client
    Description : Formulaire de demande de réinitialisation de mot de passe pour les clients avec saisie de l'email.
--}}
@extends('auth.layout')

@section('title','Mot de passe oublié - KAARJA Beauté')

@section('left')
    <div class="left-content">
        <div class="form-logo"><i class="fa fa-key"></i></div>
        <h2>Mot de passe oublié ?</h2>
        <div class="gold-line"></div>
        <p class="left-subtitle">Entrez votre email pour recevoir un lien de réinitialisation.</p>
    </div>
@endsection

@section('right')
    @if (session('status'))
        <div class="alert-luxury alert-success" role="alert">
            <i class="fa fa-check-circle mr-2"></i>{{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-luxury alert-danger" role="alert">
            <ul class="mb-0 pl-3" style="list-style:none;">
                @foreach ($errors->all() as $error)
                    <li><i class="fa fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('client.password.email') }}" autocomplete="off">
        @csrf

        <div class="form-group">
            <label for="email"><i class="fa fa-envelope"></i> Adresse email</label>
            <input type="email" class="input-luxury @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com" required autofocus>
        </div>

        <button type="submit" class="btn-luxury"><i class="fa fa-paper-plane mr-2"></i>Envoyer le lien</button>

        <div class="text-center mt-3">
            <a href="{{ route('client.login') }}" class="back-link"><i class="fa fa-arrow-left mr-1"></i> Retour à la connexion</a>
        </div>
    </form>
@endsection
