{{--
    Vue : Profil de l'employé
    Description : Page de gestion du profil employé : informations personnelles, spécialités, photo et statistiques.
--}}
@extends('layouts.employee-master')

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
        </div>

        @include('partials.success')

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="profile-photo mb-3 position-relative d-inline-block">
                            @if($employee->photo)
                                <img src="{{ asset('storage/' . $employee->photo) }}" class="img-fluid rounded-circle" width="120" height="120" style="object-fit: cover;" alt="Photo de profil">
                            @else
                                <img src="{{ asset('images/user/1.png') }}" class="img-fluid rounded-circle" width="120" height="120" alt="Photo de profil">
                            @endif
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#photoModal">
                                <i class="fa fa-camera mr-1"></i>Changer la photo
                            </button>
                            @if($employee->photo)
                                <form action="{{ route('employee.profile.photo.delete') }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer la photo ?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <h4 class="mb-1">{{ $employee->name }}</h4>
                        <p class="text-muted mb-3">{{ $employee->email }}</p>
                        <span class="badge badge-info badge-lg">
                            <i class="fa fa-user mr-1"></i>{{ ucfirst($employee->role) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Informations personnelles</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('employee.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Nom complet</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $employee->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $employee->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">Téléphone</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">🇲🇱 +223</span></div>
                                    <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $employee->phone) }}" placeholder="XX XX XX XX">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-2"></i>Enregistrer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Changer le mot de passe</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('employee.profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="current_password">Mot de passe actuel</label>
                                <input type="password" name="current_password" id="current_password"
                                       class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Nouveau mot de passe</label>
                                <input type="password" name="password" id="password"
                                       class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-warning">
                                <i class="fa fa-lock mr-2"></i>Changer le mot de passe
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title">Informations de travail</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>Rôle</th>
                                <td><span class="badge badge-info">{{ ucfirst($employee->role) }}</span></td>
                            </tr>
                            <tr>
                                <th>Statut</th>
                                <td>
                                    @if($employee->is_active)
                                        <span class="badge badge-success">Actif</span>
                                    @else
                                        <span class="badge badge-danger">Inactif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Horaires</th>
                                <td>{{ $employee->work_start_time }} - {{ $employee->work_end_time }}</td>
                            </tr>
                            <tr>
                                <th>Spécialités</th>
                                <td>{{ $employee->specialties ?? 'Non renseigné' }}</td>
                            </tr>
                            <tr>
                                <th>Membre depuis</th>
                                <td>{{ $employee->created_at->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour changer la photo -->
<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Changer la photo de profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('employee.profile.photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="photo">Sélectionner une image</label>
                        <input type="file" name="photo" id="photo" class="form-control-file" accept="image/*" required>
                        <small class="text-muted">Formats acceptés: JPEG, PNG, JPG, GIF. Taille max: 2 Mo</small>
                    </div>
                    <div id="photo-preview" class="text-center mt-3" style="display: none;">
                        <img id="preview-image" src="" class="img-fluid rounded-circle" width="150" height="150" style="object-fit: cover;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-upload mr-1"></i>Télécharger
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
    var file = e.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('photo-preview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
