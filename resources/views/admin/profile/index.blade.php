@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        {{-- WELCOME HEADER --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-user"></i></div>
                <div>
                    <h2 class="beauty-page-title">Mon Profil</h2>
                    <p class="beauty-page-subtitle">Gérez vos informations personnelles</p>
                </div>
            </div>
            <ol class="breadcrumb" style="margin:0;background:var(--primary-soft);border-radius:12px;padding:10px 18px;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Profil</li>
            </ol>
        </div>

        @include('partials.success')
@include('partials.error')

        <div class="row">
            {{-- PROFILE CARD --}}
            <div class="col-lg-4 mb-4">
                <div class="beauty-card">
                    <div class="beauty-card-body" style="text-align:center;">
                        <div class="mb-3 position-relative d-inline-block">
                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" style="width:120px;height:120px;object-fit:cover;border-radius:50%;border:4px solid var(--primary);box-shadow:0 4px 20px rgba(0,0,0,0.1);" alt="Photo de profil">
                            @else
                                <div style="width:120px;height:120px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--dark));display:inline-flex;align-items:center;justify-content:center;color:white;font-size:48px;font-family:'Playfair Display',serif;font-weight:700;border:4px solid var(--primary);box-shadow:0 4px 20px rgba(0,0,0,0.1);">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#photoModal">
                                <i class="fa fa-camera mr-1"></i>Changer la photo
                            </button>
                            @if($user->photo)
                                <form action="{{ route('admin.profile.photo.delete') }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer la photo ?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <h4 style="font-family:'Playfair Display',serif;color:var(--dark);margin-bottom:4px;">{{ $user->name }}</h4>
                        <p style="color:#8E8E8E;font-size:14px;margin-bottom:16px;">{{ $user->email }}</p>
                        <span class="badge badge-primary" style="padding:8px 16px;font-size:13px;">
                            <i class="fa fa-shield mr-1"></i>Administrateur
                        </span>

                        <div style="border-top:1px solid rgba(0,0,0,0.06);margin-top:24px;padding-top:20px;text-align:left;">
                            <p style="margin-bottom:6px;font-size:13px;"><strong><i class="fa fa-calendar mr-2" style="color:var(--primary);"></i>Membre depuis :</strong></p>
                            <p style="color:#8E8E8E;font-size:14px;margin-bottom:16px;padding-left:26px;">{{ $user->created_at->format('d/m/Y') }}</p>
                            <p style="margin-bottom:6px;font-size:13px;"><strong><i class="fa fa-clock-o mr-2" style="color:var(--primary);"></i>Dernière connexion :</strong></p>
                            <p style="color:#8E8E8E;font-size:14px;margin-bottom:0;padding-left:26px;">{{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORMS --}}
            <div class="col-lg-8">
                {{-- Personal info --}}
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-user mr-2" style="color:var(--primary);"></i>Informations personnelles</h4>
                    </div>
                    <div class="beauty-card-body">
                        <form action="{{ route('admin.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Nom complet</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Rôle</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="Administrateur" readonly disabled>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save mr-2"></i>Enregistrer les modifications
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Password --}}
                <div class="beauty-card">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-lock mr-2" style="color:var(--primary);"></i>Changer le mot de passe</h4>
                    </div>
                    <div class="beauty-card-body">
                        <form action="{{ route('admin.profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="current_password" class="col-sm-3 col-form-label">Mot de passe actuel</label>
                                <div class="col-sm-9">
                                    <input type="password" name="current_password" id="current_password"
                                           class="form-control @error('current_password') is-invalid @enderror" required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-sm-3 col-form-label">Nouveau mot de passe</label>
                                <div class="col-sm-9">
                                    <input type="password" name="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimum 8 caractères</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password_confirmation" class="col-sm-3 col-form-label">Confirmer</label>
                                <div class="col-sm-9">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fa fa-key mr-2"></i>Modifier le mot de passe
                                    </button>
                                </div>
                            </div>
                        </form>
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
            <form action="{{ route('admin.profile.photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="photo">Sélectionner une image</label>
                        <input type="file" name="photo" id="photo" class="form-control-file" accept="image/*" required>
                        <small class="text-muted">Formats acceptés: JPEG, PNG, JPG, GIF. Taille max: 2 Mo</small>
                    </div>
                    <div id="photo-preview" class="text-center mt-3" style="display: none;">
                        <img id="preview-image" src="" style="width:150px;height:150px;object-fit:cover;border-radius:50%;border:3px solid var(--primary);">
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