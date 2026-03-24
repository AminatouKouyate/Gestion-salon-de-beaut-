{{--
    Vue : Réinitialisation du mot de passe - Administrateur
    Description : Formulaire de création d'un nouveau mot de passe pour l'administrateur après validation du lien.
--}}
<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Nouveau mot de passe - Administration</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        .login-form-bg {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .login-form {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .login-form .card-body {
            padding: 40px;
        }
        .login-form h4 {
            color: #333;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .login-form .subtitle {
            color: #666;
            font-size: 14px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: #1e3c72;
            box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(30, 60, 114, 0.4);
        }
        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .logo-icon i {
            font-size: 28px;
            color: white;
        }
        .password-hint {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
    </style>
</head>
@extends('auth.layout')

@section('title','Nouveau mot de passe - KAARJA Beauté')

@section('left')
    <div class="left-content">
        <div class="form-logo"><i class="fa fa-lock"></i></div>
        <h2>Nouveau mot de passe</h2>
        <div class="gold-line"></div>
        <p class="left-subtitle">Créez un nouveau mot de passe sécurisé</p>
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

    <form method="POST" action="{{ route('admin.password.update') }}" autocomplete="off">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email"><i class="fa fa-envelope"></i> Adresse email</label>
            <input type="email" class="input-luxury @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $email ?? '') }}" placeholder="admin@kaarja.com" required autofocus>
        </div>

        <div class="form-group">
            <label for="password"><i class="fa fa-lock"></i> Nouveau mot de passe</label>
            <input type="password" class="input-luxury @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
            <small class="password-hint">Minimum 8 caractères</small>
        </div>

        <div class="form-group">
            <label for="password_confirmation"><i class="fa fa-lock"></i> Confirmer le mot de passe</label>
            <input type="password" class="input-luxury" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-luxury"><i class="fa fa-save mr-2"></i>Enregistrer le mot de passe</button>

        <div class="text-center mt-3"><a href="{{ route('login') }}" class="back-link"><i class="fa fa-arrow-left mr-1"></i> Retour à la connexion</a></div>
    </form>
@endsection
                            <div class="form-group">
