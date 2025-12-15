<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tableau de bord</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link href="/css/style.css" rel="stylesheet">
</head>
<body>

    {{-- Inclure la sidebar qui s'adaptera au rôle de l'utilisateur --}}
    @include('partials.sidebar')

    <div class="content-body" style="min-height: 100vh;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1>Bienvenue, {{ auth()->user()->name }} !</h1>
                    <p>Vous êtes connecté avec le rôle : <strong>{{ auth()->user()->role }}</strong>.</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Se déconnecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
