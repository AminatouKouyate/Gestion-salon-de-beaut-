{{--
    Vue : Changement de mot de passe employé
    Description : Formulaire de modification du mot de passe de l'employé avec vérification de l'ancien mot de passe.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-lock"></i></div>
                <div>
                    <h2 class="beauty-page-title">Changer mon mot de passe</h2>
                    <p class="beauty-page-subtitle">Modifiez votre mot de passe de connexion</p>
                </div>
            </div>
        </div>

        @include('partials.success')
        @include('partials.error')

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Modification du mot de passe</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('employee.password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="current_password">Mot de passe actuel <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Nouveau mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Le mot de passe doit contenir au moins 8 caractères, avec des majuscules, minuscules et chiffres.
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirmer le nouveau mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Mettre à jour le mot de passe</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Conseils de sécurité</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fa fa-check-circle text-success mr-2"></i>
                                Utilisez au moins 8 caractères
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-check-circle text-success mr-2"></i>
                                Incluez des majuscules et minuscules
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-check-circle text-success mr-2"></i>
                                Ajoutez des chiffres
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-check-circle text-success mr-2"></i>
                                Évitez les mots courants
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-check-circle text-success mr-2"></i>
                                Changez régulièrement votre mot de passe
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
