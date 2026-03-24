@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Détails de la demande de congé</h1>
            <a href="{{ route('admin.leaves.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>

        @include('partials.success')
@include('partials.error')

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informations de l'employé</h5>
                        <table class="table">
                            <tr>
                                <th>Nom</th>
                                <td>{{ $leave->employee->name ?? '�' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $leave->employee->email ?? '�' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Détails du congé</h5>
                        <table class="table">
                            <tr>
                                <th>Date de début</th>
                                <td>{{ $leave->start_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Date de fin</th>
                                <td>{{ $leave->end_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Durée</th>
                                <td>{{ $leave->days_count }} jour(s)</td>
                            </tr>
                            <tr>
                                <th>Statut</th>
                                <td>
                                    @if($leave->status === 'pending')
                                        <span class="badge bg-warning">En attente</span>
                                    @elseif($leave->status === 'approved')
                                        <span class="badge bg-success">Approuvée</span>
                                    @else
                                        <span class="badge bg-danger">Refusée</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Motif de la demande</h5>
                        <p class="border p-3 rounded bg-light">{{ $leave->reason }}</p>
                    </div>
                </div>

                @if($leave->admin_response)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Réponse de l'administration</h5>
                        <p class="border p-3 rounded {{ $leave->status === 'approved' ? 'bg-success-light' : 'bg-danger-light' }}">
                            {{ $leave->admin_response }}
                        </p>
                        <small class="text-muted">Répondu le {{ $leave->responded_at->format('d/m/Y à H:i') }}</small>
                    </div>
                </div>
                @endif

                @if($leave->status === 'pending')
                <div class="row mt-4">
                    <div class="col-12">
                        <hr>
                        <h5>Actions</h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                <i class="fa fa-check"></i> Approuver
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fa fa-times"></i> Refuser
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Approuver -->
                <div class="modal fade" id="approveModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.leaves.approve', $leave) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-header">
                                    <h5 class="modal-title">Approuver la demande</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Commentaire (optionnel)</label>
                                        <textarea name="admin_response" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-success">Approuver</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Refuser -->
                <div class="modal fade" id="rejectModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.leaves.reject', $leave) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-header">
                                    <h5 class="modal-title">Refuser la demande</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Motif du refus <span class="text-danger">*</span></label>
                                        <textarea name="admin_response" class="form-control" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-danger">Refuser</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
