{{--
    Vue : Profil du client
    Description : Page de gestion du profil client : photo, informations personnelles, allergies/sensibilités, changement de mot de passe, programme de fidélité (points, niveau, progression), statistiques et zone de désactivation du compte.
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-user"></i></div>
                <div>
                    <h2 class="beauty-page-title">Mon Profil</h2>
                    <p class="beauty-page-subtitle">Gérez vos informations personnelles</p>
                </div>
            </div>
            <a href="{{ route('client.dashboard') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour au dashboard</a>
        </div>

        @include('partials.success')
        @include('partials.error')

        @php
            $loyaltyLevel = $client->getLoyaltyLevel();
            $loyaltyBadgeClass = match($loyaltyLevel) {
                'Platine' => 'primary',
                'Or' => 'warning',
                'Argent' => 'info',
                default => 'secondary',
            };
            $currentPoints = $client->loyalty_points ?? 0;
            $nextLevel = match($loyaltyLevel) {
                'Bronze' => ['name' => 'Argent', 'points' => 100],
                'Argent' => ['name' => 'Or', 'points' => 200],
                'Or' => ['name' => 'Platine', 'points' => 500],
                default => null,
            };
            $levelStart = match($loyaltyLevel) {
                'Bronze' => 0,
                'Argent' => 100,
                'Or' => 200,
                'Platine' => 500,
                default => 0,
            };
            $progressPercent = $nextLevel 
                ? round((($currentPoints - $levelStart) / ($nextLevel['points'] - $levelStart)) * 100)
                : 100;
            $loyaltyLevels = [
                ['name' => 'Bronze', 'min' => 0, 'discount' => 0],
                ['name' => 'Argent', 'min' => 100, 'discount' => 10],
                ['name' => 'Or', 'min' => 200, 'discount' => 15],
                ['name' => 'Platine', 'min' => 500, 'discount' => 20],
            ];
        @endphp

        <div class="row">
            <div class="col-lg-8">
                <!-- Section Photo de Profil -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">Photo de profil</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if($client->photo)
                                <img src="{{ asset('storage/' . $client->photo) }}" 
                                     alt="Photo de profil" 
                                     class="rounded-circle" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" 
                                     style="width: 150px; height: 150px;">
                                    <i class="fa fa-user text-white" style="font-size: 60px;"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#photoModal">
                                <i class="fa fa-camera mr-2"></i>Changer la photo
                            </button>
                            @if($client->photo)
                                <form action="{{ route('client.profile.photo.delete') }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Supprimer la photo ?')">
                                        <i class="fa fa-trash mr-2"></i>Supprimer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

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
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">🇲🇱 +223</span>
                                            </div>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone" name="phone" value="{{ old('phone', $client->phone) }}"
                                                   placeholder="XX XX XX XX">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
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
                            <div class="card border-danger mb-4">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0"><i class="fa fa-exclamation-triangle mr-2"></i>Allergies & Sensibilités</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-0">
                                        <label for="allergies" class="text-danger font-weight-bold">
                                            <i class="fa fa-warning mr-1"></i>Indiquez vos allergies connues
                                        </label>
                                        <textarea class="form-control border-danger @error('allergies') is-invalid @enderror"
                                                  id="allergies" name="allergies" rows="3"
                                                  placeholder="Ex: Allergie aux parfums, sensibilité au latex, réaction aux colorants capillaires...">{{ old('allergies', $client->allergies) }}</textarea>
                                        <small class="text-danger">
                                            <i class="fa fa-info-circle mr-1"></i>Ces informations seront communiquées aux employés pour votre sécurité
                                        </small>
                                        @error('allergies')
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
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fa fa-star mr-2"></i>Programme Fidélité
                        </h4>
                    </div>
                    <div class="card-body text-center">
                        <h1 class="text-{{ $loyaltyBadgeClass }} mb-0">{{ $currentPoints }}</h1>
                        <p class="text-muted">Points accumulés</p>

                        <span class="badge badge-{{ $loyaltyBadgeClass }} badge-lg px-3 py-2" style="font-size: 1rem;">
                            Niveau {{ $loyaltyLevel }}
                        </span>

                        @if($client->getLoyaltyDiscount() > 0)
                            <p class="mt-3 mb-0">
                                <i class="fa fa-percent text-success"></i>
                                <strong>{{ $client->getLoyaltyDiscount() }}% de réduction</strong>
                            </p>
                        @endif

                        @if($nextLevel)
                            <hr>
                            <p class="text-muted small mb-2">Progression vers {{ $nextLevel['name'] }}</p>
                            <div class="progress mb-2" style="height: 20px;">
                                <div class="progress-bar bg-{{ $loyaltyBadgeClass }}" role="progressbar"
                                     style="width: {{ $progressPercent }}%;"
                                     aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $progressPercent }}%
                                </div>
                            </div>
                            <p class="text-muted small mb-0">
                                Plus que {{ $nextLevel['points'] - $currentPoints }} points
                            </p>
                        @else
                            <hr>
                            <div class="alert alert-success mb-0">
                                <i class="fa fa-trophy mr-2"></i>Niveau maximum atteint !
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">Niveaux de fidélité</h4>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($loyaltyLevels as $level)
                                @php
                                    $levelBadge = match($level['name']) {
                                        'Platine' => 'primary',
                                        'Or' => 'warning',
                                        'Argent' => 'info',
                                        default => 'secondary',
                                    };
                                    $isCurrentLevel = $loyaltyLevel === $level['name'];
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center {{ $isCurrentLevel ? 'bg-light border-left border-' . $loyaltyBadgeClass : '' }}" style="{{ $isCurrentLevel ? 'border-left-width: 4px !important;' : '' }}">
                                    <div>
                                        <span class="badge badge-{{ $levelBadge }} mr-2">{{ $level['name'] }}</span>
                                        <small class="text-muted">{{ $level['min'] }}+ points</small>
                                    </div>
                                    <span class="{{ $level['discount'] > 0 ? 'text-success font-weight-bold' : 'text-muted' }}">
                                        {{ $level['discount'] > 0 ? $level['discount'] . '%' : '-' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
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

<!-- Modal Photo de Profil -->
<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="photoModalLabel">
                    <i class="fa fa-camera mr-2"></i>Changer la photo de profil
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('client.profile.photo') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="photo">Sélectionner une photo</label>
                        <input type="file" class="form-control-file @error('photo') is-invalid @enderror" 
                               id="photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/gif" required>
                        <small class="form-text text-muted">
                            Formats acceptés : JPEG, PNG, JPG, GIF. Taille maximale : 2 Mo.
                        </small>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="photoPreview" class="text-center mt-3" style="display: none;">
                        <img id="previewImage" src="" alt="Aperçu" class="rounded-circle" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-upload mr-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="deactivateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg, #dc3545, #c82333) !important;">
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

@push('scripts')
<script>
document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('photoPreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
