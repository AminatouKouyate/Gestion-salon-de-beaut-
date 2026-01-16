@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Mon Profil</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('client.dashboard') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-2"></i>Retour au dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Informations personnelles</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('client.profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nom complet <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name', $client->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email', $client->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Téléphone</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                               id="phone" name="phone" value="{{ old('phone', $client->phone) }}"
                                               placeholder="01 23 45 67 89">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Adresse</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                               id="address" name="address" value="{{ old('address', $client->address) }}"
                                               placeholder="123 Rue Example, 75001 Paris">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3">Changer le mot de passe</h5>
                            <p class="text-muted small">Laissez vide si vous ne souhaitez pas changer votre mot de passe</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Nouveau mot de passe</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password" placeholder="••••••••">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirmer le mot de passe</label>
                                        <input type="password" class="form-control"
                                               id="password_confirmation" name="password_confirmation" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa fa-save mr-2"></i>Mettre à jour le profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h4 class="card-title text-white mb-0">
                            <i class="fa fa-star mr-2"></i>Programme Fidélité
                        </h4>
                    </div>
                    <div class="card-body text-center">
                        <h1 class="text-primary mb-0">{{ $client->loyalty_points ?? 0 }}</h1>
                        <p class="text-muted">Points accumulés</p>

                        <div class="progress mb-3" style="height: 20px;">
                            @php $progress = min(($client->loyalty_points ?? 0), 100); @endphp
                            <div class="progress-bar bg-primary" role="progressbar"
                                 style="width: {{ $progress }}%;"
                                 aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $progress }}%
                            </div>
                        </div>

                        @if(($client->loyalty_points ?? 0) >= 100)
                            <div class="alert alert-success mb-0">
                                <i class="fa fa-gift mr-2"></i>Réduction de 10% disponible !
                            </div>
                        @else
                            <p class="text-muted small mb-0">
                                Plus que {{ 100 - ($client->loyalty_points ?? 0) }} points pour une réduction de 10%
                            </p>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">Statistiques</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex justify-content-between">
                                <span><i class="fa fa-calendar-check-o text-success mr-2"></i>Total rendez-vous</span>
                                <strong>{{ $client->total_appointments ?? 0 }}</strong>
                            </li>
                            <li class="mb-3 d-flex justify-content-between">
                                <span><i class="fa fa-user-plus text-info mr-2"></i>Membre depuis</span>
                                <strong>{{ $client->created_at ? $client->created_at->format('d/m/Y') : 'N/A' }}</strong>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span><i class="fa fa-check-circle text-primary mr-2"></i>Statut</span>
                                <span class="badge badge-{{ $client->active ? 'success' : 'secondary' }}">
                                    {{ $client->active ? 'Actif' : 'Inactif' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-danger">
                    <div class="card-header bg-danger">
                        <h4 class="card-title text-white mb-0">
                            <i class="fa fa-exclamation-triangle mr-2"></i>Zone de danger
                        </h4>
                    </div>
                    <div class="card-body">
                        <h5 class="text-danger">Désactiver mon compte</h5>
                        <p class="text-muted">
                            Une fois votre compte désactivé, vous ne pourrez plus accéder à votre espace client.
                            Contactez le salon pour réactiver votre compte.
                        </p>
                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deactivateModal">
                            <i class="fa fa-ban mr-2"></i>Désactiver mon compte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="deactivateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="deactivateModalLabel">
                    <i class="fa fa-exclamation-triangle mr-2"></i>Confirmer la désactivation
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('client.profile.deactivate') }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fa fa-warning mr-2"></i>
                        <strong>Attention !</strong> Cette action désactivera votre compte. Vous ne pourrez plus vous connecter.
                    </div>
                    <p>Pour confirmer, veuillez entrer votre mot de passe :</p>
                    <div class="form-group">
                        <label for="deactivate_password">Mot de passe actuel</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="deactivate_password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-ban mr-2"></i>Désactiver mon compte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
