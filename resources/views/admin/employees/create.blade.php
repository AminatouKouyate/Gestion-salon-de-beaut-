{{--
    Vue : Création d'un employé
    Route : admin.employees.create
    Contrôleur : EmployeeController@create
    Description : Formulaire d'ajout d'un nouvel employé avec informations personnelles,
                  horaires de travail, jours de travail et services assignés.
--}}
@extends('layouts.admin-master')

{{-- Section : Contenu principal --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        {{-- Section : En-tête de page --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-user-plus"></i></div>
                <div>
                    <h2 class="beauty-page-title">Ajouter un employé </h2>
                    <p class="beauty-page-subtitle">Enregistrer un nouveau membre</p>
                </div>
            </div>
            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-2"></i>Retour à la liste
            </a>
        </div>

        {{-- Section : Messages de succès et d'erreur --}}
        @include('partials.success')
        @include('partials.error')

        {{-- Section : Formulaire de création --}}
        <div class="beauty-card">
            <div class="beauty-card-body">
                <form action="{{ route('admin.employees.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        {{-- Section : Informations personnelles --}}
                        <div class="col-md-6">
                            <h5 class="mb-3">Informations personnelles</h5>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">🇲🇱 +223</span></div>
                                    <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="XX XX XX XX">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="employee" @selected(old('role') == 'employee')>Employé</option>
                                    <option value="manager" @selected(old('role') == 'manager')>Manager</option>
                                </select>
                                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="specialties" class="form-label">Spécialités</label>
                                <textarea name="specialties" id="specialties" class="form-control @error('specialties') is-invalid @enderror" rows="3">{{ old('specialties') }}</textarea>
                                @error('specialties')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" @checked(old('is_active', true))>
                                <label for="is_active" class="form-check-label">Actif</label>
                            </div>
                        </div>

                        {{-- Section : Horaires de travail et services assignés --}}
                        <div class="col-md-6">
                            <h5 class="mb-3">Horaires de travail</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="work_start_time" class="form-label">Heure de début</label>
                                    <input type="time" name="work_start_time" id="work_start_time" class="form-control @error('work_start_time') is-invalid @enderror" value="{{ old('work_start_time', '09:00') }}">
                                    @error('work_start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="work_end_time" class="form-label">Heure de fin</label>
                                    <input type="time" name="work_end_time" id="work_end_time" class="form-control @error('work_end_time') is-invalid @enderror" value="{{ old('work_end_time', '18:00') }}">
                                    @error('work_end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Jours de travail</label>
                                @error('work_days')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                                <div class="row">
                                    @php
                                        $days = [
                                            'monday' => 'Lundi',
                                            'tuesday' => 'Mardi',
                                            'wednesday' => 'Mercredi',
                                            'thursday' => 'Jeudi',
                                            'friday' => 'Vendredi',
                                            'saturday' => 'Samedi',
                                            'sunday' => 'Dimanche',
                                        ];
                                        $defaultDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                                    @endphp
                                    @foreach($days as $value => $label)
                                        <div class="col-6 col-md-4">
                                            <div class="form-check">
                                                <input type="checkbox" name="work_days[]" id="day_{{ $value }}" value="{{ $value }}" class="form-check-input" @checked(in_array($value, old('work_days', $defaultDays)))>
                                                <label for="day_{{ $value }}" class="form-check-label">{{ $label }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <h5 class="mb-3">Services assignés</h5>
                            @error('services')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                @forelse($services as $service)
                                    <div class="form-check">
                                        <input type="checkbox" name="services[]" id="service_{{ $service->id }}" value="{{ $service->id }}" class="form-check-input" @checked(in_array($service->id, old('services', [])))>
                                        <label for="service_{{ $service->id }}" class="form-check-label">
                                            {{ $service->name }}
                                            <span class="text-muted">({{ number_format($service->price, 0, ',', ' ') }} FCFA - {{ $service->duration }} min)</span>
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">Aucun service disponible</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <button type="submit" class="btn btn-primary">Ajouter l'employé</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
