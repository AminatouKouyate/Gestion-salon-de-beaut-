@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-credit-card"></i></div>
                <div>
                    <h2 class="beauty-page-title">Paiements </h2>
                    <p class="beauty-page-subtitle">Liste de toutes les transactions</p>
                </div>
            </div>
        </div>

        @include('partials.success')
        @include('partials.error')

        <div class="beauty-card mb-4">
            <div class="beauty-card-header">
                <h4><i class="fa fa-money mr-2" style="color:var(--primary);"></i>Liste des paiements</h4>
            </div>
            <div class="beauty-card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Rendez-vous</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr>
                                <td><strong>{{ $payment->client->name ?? '—' }}</strong></td>
                                <td><span class="badge badge-secondary">{{ $payment->appointment->service->name ?? '—' }}</span></td>
                                <td><strong class="text-primary">{{ number_format($payment->amount, 0, ',', ' ') }}</strong> <small>FCFA</small></td>
                                <td>{!! $payment->status_badge !!}</td>
                                <td><i class="fa fa-clock-o mr-1" style="color:var(--primary-light);"></i>{{ $payment->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-primary" title="Voir détails">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="beauty-empty">
                                        <div class="beauty-empty-icon"><i class="fa fa-credit-card"></i></div>
                                        <p>Aucun paiement trouvé</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($payments->hasPages())
            <div class="beauty-card-footer">
                {{ $payments->links() }}
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
