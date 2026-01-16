<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Connexion Employé - Rosella</title>
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
                                <h4>Connexion Employé</h4>
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

                            <form class="mt-5 mb-5 login-input" method="POST" action="{{ route('employee.login') }}">
                                @csrf

                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required>
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
                                </div>

                                <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                    <div class="form-group">
                                        {{-- Le lien pour la réinitialisation du mot de passe employé sera ajouté ici --}}
                                        {{-- <a href="#">Mot de passe oublié ?</a> --}}
                                    </div>
                                </div>

                                <button class="btn login-form__btn submit w-100" type="submit">Se connecter</button>
                            </form>

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
