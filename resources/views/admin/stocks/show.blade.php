@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-cube"></i></div>
                <div>
                    <h2 class="beauty-page-title">{{ $stock->name }}</h2>
                    <p class="beauty-page-subtitle">Détails du produit en stock</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.stocks.edit', $stock) }}" class="btn btn-primary"><i class="fa fa-pencil mr-2"></i>Modifier</a>
                <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
            </div>
        </div>

        @include('partials.success')
        @include('partials.error')

        <div class="row">
            <div class="col-lg-8">
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h5 class="mb-0"><i class="fa fa-info-circle mr-2" style="color:var(--primary);"></i>Informations</h5>
                    </div>
                    <div class="beauty-card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" style="width:180px;">Nom</td>
                                    <td><strong>{{ $stock->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Catégorie</td>
                                    <td>
                                        @if($stock->category)
                                            <span class="badge badge-info">{{ $stock->category }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Quantité</td>
                                    <td>
                                        <span class="stock-quantity {{ $stock->quantity <= 0 ? 'stock-qty-danger' : ($stock->quantity <= $stock->alert_threshold ? 'stock-qty-warning' : 'stock-qty-ok') }}">
                                            {{ $stock->quantity }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Seuil d'alerte</td>
                                    <td><span class="stock-threshold">{{ $stock->alert_threshold }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Statut</td>
                                    <td>
                                        @if($stock->quantity <= 0)
                                            <span class="badge badge-danger"><i class="fa fa-times mr-1"></i>Rupture</span>
                                        @elseif($stock->quantity <= $stock->alert_threshold)
                                            <span class="badge badge-warning"><i class="fa fa-exclamation mr-1"></i>Faible</span>
                                        @else
                                            <span class="badge badge-success"><i class="fa fa-check mr-1"></i>OK</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Date d'ajout</td>
                                    <td>{{ $stock->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Dernière modification</td>
                                    <td>{{ $stock->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.stock-quantity {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 48px;
    padding: 6px 14px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 700;
    font-family: 'Playfair Display', serif;
}
.stock-qty-ok { background: #E8F5E9; color: #2D8B61; }
.stock-qty-warning { background: #FFF3D6; color: #c49b2a; }
.stock-qty-danger { background: #FFE8EC; color: #E74C5F; }

.stock-threshold {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 8px;
    background: #f0f0f0;
    color: #666;
    font-size: 14px;
    font-weight: 500;
}

.dark-theme .stock-qty-ok { background: rgba(45, 139, 97, 0.15); color: #5cdb95; }
.dark-theme .stock-qty-warning { background: rgba(212, 175, 55, 0.15); color: #f0d060; }
.dark-theme .stock-qty-danger { background: rgba(231, 76, 95, 0.15); color: #f08090; }
.dark-theme .stock-threshold { background: #333355; color: #aaa; }
</style>
@endsection