{{--
    Vue : Liste des demandes de congé
    Route : admin.leaves.index
    Contrôleur : LeaveRequestController@index
    Description : Affiche la liste des demandes de congé des employés avec filtrage
                  par statut, statistiques et modales d'approbation/refus.
--}}
@extends('layouts.admin-master')

{{-- Section : Contenu principal --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">

        {{-- Section : En-tête de page --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-calendar-times-o"></i></div>
                <div>
                    <h2 class="beauty-page-title">Demandes de congé</h2>
                    <p class="beauty-page-subtitle">Gérez les demandes de congé des employés</p>
                </div>
            </div>
        </div>

        {{-- Section : Messages de succès et d'erreur --}}
        @include('partials.success')
        @include('partials.error')

        {{-- Section : Statistiques des demandes --}}
        <div class="row mb-4">
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon gold"><i class="fa fa-clock-o"></i></div>
                    <div>
                        <h3>{{ $pendingCount }}</h3>
                        <p>En attente</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon green"><i class="fa fa-check-circle"></i></div>
                    <div>
                        <h3>{{ $approvedCount }}</h3>
                        <p>Approuvées</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon rose"><i class="fa fa-times-circle"></i></div>
                    <div>
                        <h3>{{ $rejectedCount }}</h3>
                        <p>Refusées</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section : Tableau des données avec filtres --}}
        <div class="beauty-card mb-4">
            <div class="beauty-card-header">
                <h4><i class="fa fa-list mr-2" style="color:var(--primary);"></i>Liste des demandes</h4>
                <div class="d-flex" style="gap:6px;">
                    <a href="{{ route('admin.leaves.index') }}" class="btn btn-sm {{ !request('status') || request('status') === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">Toutes</a>
                    <a href="{{ route('admin.leaves.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">En attente</a>
                    <a href="{{ route('admin.leaves.index', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-success' }}">Approuvées</a>
                    <a href="{{ route('admin.leaves.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">Refusées</a>
                </div>
            </div>
            <div class="beauty-card-body">
                @if($leaveRequests->isEmpty())
                <div class="beauty-empty">
                    <div class="beauty-empty-icon"><i class="fa fa-calendar-times-o"></i></div>
                    <p>Aucune demande de congé</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Durée</th>
                                <th>Motif</th>
                                <th>Statut</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveRequests as $leave)
                            <tr>
                                <td><strong>{{ $leave->employee->name ?? '—' }}</strong></td>
                                <td>{{ $leave->start_date->format('d/m/Y') }}</td>
                                <td>{{ $leave->end_date->format('d/m/Y') }}</td>
                                <td>{{ $leave->days_count }} jour(s)</td>
                                <td>{{ Str::limit($leave->reason, 30) }}</td>
                                <td>
                                    @if($leave->status === 'pending')
                                        <span class="badge badge-warning">En attente</span>
                                    @elseif($leave->status === 'approved')
                                        <span class="badge badge-success">Approuvée</span>
                                    @else
                                        <span class="badge badge-danger">Refusée</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.leaves.show', $leave) }}" class="btn btn-sm btn-info" title="Voir"><i class="fa fa-eye"></i></a>
                                    @if($leave->status === 'pending')
                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#approveModal" data-leave-id="{{ $leave->id }}" data-leave-name="{{ $leave->employee->name ?? '—' }}" data-leave-dates="{{ $leave->start_date->format('d/m/Y') }} - {{ $leave->end_date->format('d/m/Y') }}" data-leave-reason="{{ $leave->reason }}" title="Approuver">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal" data-leave-id="{{ $leave->id }}" data-leave-name="{{ $leave->employee->name ?? '—' }}" data-leave-dates="{{ $leave->start_date->format('d/m/Y') }} - {{ $leave->end_date->format('d/m/Y') }}" data-leave-reason="{{ $leave->reason }}" title="Refuser">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            @if($leaveRequests->hasPages())
            <div class="beauty-card-footer">
                {{ $leaveRequests->links() }}
            </div>
            @endif
        </div>

    </div>
</div>

{{-- Section : Modale d'approbation de la demande --}}
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-check-circle mr-2"></i>Approuver la demande</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p><strong>Employé :</strong> <span id="approveEmployeeName"></span></p>
                    <p><strong>Période :</strong> <span id="approveDates"></span></p>
                    <p><strong>Motif :</strong> <span id="approveReason"></span></p>
                    <div class="form-group">
                        <label>Commentaire (optionnel)</label>
                        <textarea name="admin_response" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check mr-2"></i>Approuver</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Section : Modale de refus de la demande --}}
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-times-circle mr-2"></i>Refuser la demande</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p><strong>Employé :</strong> <span id="rejectEmployeeName"></span></p>
                    <p><strong>Période :</strong> <span id="rejectDates"></span></p>
                    <p><strong>Motif :</strong> <span id="rejectReason"></span></p>
                    <div class="form-group">
                        <label>Motif du refus <span class="text-danger">*</span></label>
                        <textarea name="admin_response" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger"><i class="fa fa-times mr-2"></i>Refuser</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
/**
 * Script de gestion des modales d'approbation et de refus de congé.
 * Renseigne dynamiquement les informations de la demande dans la modale
 * et configure l'URL d'action du formulaire selon l'identifiant du congé.
 */
document.addEventListener('DOMContentLoaded', function() {
    // Ouverture de la modale d'approbation : remplissage des données
    $('#approveModal').on('show.bs.modal', function(event) {
        var btn = $(event.relatedTarget);
        var id = btn.data('leave-id');
        $('#approveEmployeeName').text(btn.data('leave-name'));
        $('#approveDates').text(btn.data('leave-dates'));
        $('#approveReason').text(btn.data('leave-reason'));
        $('#approveForm').attr('action', '/admin/leaves/' + id + '/approve');
    });

    // Ouverture de la modale de refus : remplissage des données
    $('#rejectModal').on('show.bs.modal', function(event) {
        var btn = $(event.relatedTarget);
        var id = btn.data('leave-id');
        $('#rejectEmployeeName').text(btn.data('leave-name'));
        $('#rejectDates').text(btn.data('leave-dates'));
        $('#rejectReason').text(btn.data('leave-reason'));
        $('#rejectForm').attr('action', '/admin/leaves/' + id + '/reject');
    });
});
</script>
@endsection
