<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mot de passe oublié - Rosella</title>
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
                                <h4>Mot de passe oublié</h4>
                            </a>

                            <p class="text-center mt-3 mb-4">
                                Entrez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                            </p>

                            @if (session('status'))
                                <div class="alert alert-success mt-3">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form class="mt-4 mb-5 login-input" method="POST" action="{{ route('client.password.email') }}">
                                @csrf

                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                                </div>

                                <button class="btn login-form__btn submit w-100" type="submit">Envoyer le lien de réinitialisation</button>
                            </form>

                            <p class="text-center">
                                <a href="{{ route('client.login') }}">Retour à la connexion</a>
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
