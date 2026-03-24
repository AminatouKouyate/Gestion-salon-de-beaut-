{{--
    Vue : Liste des employés
    Route : admin.employees.index
    Contrôleur : EmployeeController@index
    Description : Affiche la liste paginée de tous les employés du salon avec
                  leurs informations, horaires, jours de travail et actions.
--}}
@extends('layouts.admin-master')

{{-- Section : Contenu principal --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">

        {{-- Section : En-tête de page --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-users"></i></div>
                <div>
                    <h2 class="beauty-page-title">Employés </h2>
                    <p class="beauty-page-subtitle">Gérez l'équipe du salon</p>
                </div>
            </div>
            <a href="{{ route('admin.employees.create') }}" class="beauty-btn-primary">
                <i class="fa fa-plus mr-2"></i>Ajouter un employé
            </a>
        </div>

        {{-- Section : Messages de succès et d'erreur --}}
        @include('partials.success')
        @include('partials.error')

        {{-- Section : Tableau des données --}}
        <div class="beauty-card mb-4">
            <div class="beauty-card-header">
                <h4><i class="fa fa-id-card mr-2" style="color:var(--primary);"></i>Tous les employés</h4>
            </div>
            <div class="beauty-card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Horaires</th>
                                <th>Jours de travail</th>
                                <th>Statut</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                            @php
                            $dayNames = ['monday'=>'Lun','tuesday'=>'Mar','wednesday'=>'Mer','thursday'=>'Jeu','friday'=>'Ven','saturday'=>'Sam','sunday'=>'Dim'];
                            @endphp
                            <tr>
                                <td><strong>{{ $employee->name }}</strong></td>
                                <td>{{ $employee->email }}</td>
                                <td><span class="badge badge-info">{{ ucfirst($employee->role) }}</span></td>
                                <td>
                                    @if($employee->work_start_time && $employee->work_end_time)
                                    <span class="text-nowrap"><i class="fa fa-clock-o mr-1" style="color:var(--primary);"></i>{{ \Carbon\Carbon::parse($employee->work_start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($employee->work_end_time)->format('H:i') }}</span>
                                    @else
                                    <span class="text-muted"><i class="fa fa-exclamation-circle mr-1"></i>Non défini</span>
                                    @endif
                                </td>
                                <td>
                                    @if($employee->work_days && count($employee->work_days) > 0)
                                        @foreach($employee->work_days as $day)
                                            <span class="badge badge-primary badge-pill mr-1">{{ $dayNames[$day] ?? $day }}</span>
                                        @endforeach
                                    @else
                                    <span class="text-muted"><i class="fa fa-exclamation-circle mr-1"></i>Non défini</span>
                                    @endif
                                </td>
                                <td>
                                    @if($employee->is_active)
                                    <span class="badge badge-success"><i class="fa fa-check mr-1"></i>Actif</span>
                                    @else
                                    <span class="badge badge-danger"><i class="fa fa-times mr-1"></i>Inactif</span>
                                    @endif
                                </td>
                                {{-- Section : Actions (modifier, supprimer, activer/désactiver) --}}
                                <td class="text-right">
                                    @include('partials.actions', ['employee' => $employee])
                                    @if($employee->is_active)
                                        <form action="{{ route('admin.employees.toggle-active', $employee) }}" method="POST" class="d-inline confirm-toggle" data-confirm-message="Êtes-vous sûr de vouloir désactiver l'employé {{ $employee->name }} ?" data-confirm-title="Désactiver l'employé" data-confirm-btn="btn-warning" data-confirm-btn-text="Désactiver">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning" title="Désactiver"><i class="fa fa-ban"></i></button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.employees.toggle-active', $employee) }}" method="POST" class="d-inline confirm-toggle" data-confirm-message="Êtes-vous sûr de vouloir réactiver l'employé {{ $employee->name }} ?" data-confirm-title="Réactiver l'employé" data-confirm-btn="btn-success" data-confirm-btn-text="Réactiver">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Activer"><i class="fa fa-check"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="beauty-empty">
                                        <div class="beauty-empty-icon"><i class="fa fa-users"></i></div>
                                        <p>Aucun employé trouvé</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Section : Pagination --}}
            @if($employees->hasPages())
            <div class="beauty-card-footer">{{ $employees->links() }}</div>
            @endif
        </div>

    </div>
</div>
@endsection
