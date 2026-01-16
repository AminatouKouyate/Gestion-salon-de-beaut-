<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Inscription Client - Rosella</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body class="h-100">

<div id="preloader">
    <div class="loader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"/>
        </svg>
    </div>
</div>

<div class="login-form-bg h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100">
            <div class="col-xl-6">
                <div class="form-input-content">
                    <div class="card login-form mb-0">
                        <div class="card-body pt-5">

                            <a class="text-center" href="#">
                                <h4>Inscription Client</h4>
                            </a>

                            @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form class="mt-5 mb-5 login-input" method="POST" action="{{ route('client.register') }}">
                                @csrf

                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" placeholder="Nom complet" value="{{ old('name') }}" required autofocus>
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required>
                                </div>

                                <div class="form-group">
                                    <input type="tel" class="form-control" name="phone" placeholder="Téléphone (optionnel)" value="{{ old('phone') }}">
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Mot de passe (min. 8 caractères)" required>
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmer le mot de passe" required>
                                </div>

                                <button class="btn login-form__btn submit w-100" type="submit">S'inscrire</button>
                            </form>
                            <p class="mt-3 text-center">Déjà un compte ? <a href="{{ route('client.login') }}">Connectez-vous</a></p>

                            <hr>

                            <p class="text-center mt-3">
                                <a href="{{ route('home') }}" class="text-muted">
                                    <i class="fa fa-arrow-left"></i> Retour à l'accueil
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('plugins/common/common.min.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>

</body>
</html>
