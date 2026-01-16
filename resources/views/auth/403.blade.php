<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Erreur 403 - Accès Interdit</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="h-100">
    <div class="h-100 d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg border-0">
                        <div class="card-body text-center p-5">
                            <h1 class="display-1 font-weight-bold text-danger">403</h1>
                            <h4 class="mb-4 mt-2">Accès Interdit</h4>
                            <p class="text-muted mb-4">
                                {{ $exception->getMessage() ?: "Désolé, vous n'avez pas la permission d'accéder à cette page." }}
                            </p>
                            <a href="{{ url('/') }}" class="btn btn-primary px-4">
                                Retour à l'accueil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
