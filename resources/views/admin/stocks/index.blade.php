@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-cubes"></i></div>
                <div>
                    <h2 class="beauty-page-title">Stocks </h2>
                    <p class="beauty-page-subtitle">Gérez les produits et fournitures du salon</p>
                </div>
            </div>
            <a href="{{ route('admin.stocks.create') }}" class="beauty-btn-primary">
                <i class="fa fa-plus mr-2"></i>Ajouter un produit
            </a>
        </div>

        @include('partials.success')
        @include('partials.error')

        {{-- STATS ROW --}}
        @php
            $totalProducts = $stocks->count();
            $availableCount = $stocks->filter(fn($s) => $s->quantity > $s->alert_threshold)->count();
            $lowCount = $lowStocks->count();
            $outOfStock = $stocks->filter(fn($s) => $s->quantity <= 0)->count();
        @endphp
        <div class="row mb-4">
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon rose"><i class="fa fa-cubes"></i></div>
                    <div>
                        <h3>{{ $totalProducts }}</h3>
                        <p>Total produits</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon green"><i class="fa fa-check-circle"></i></div>
                    <div>
                        <h3>{{ $availableCount }}</h3>
                        <p>Disponibles</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon gold"><i class="fa fa-exclamation-triangle"></i></div>
                    <div>
                        <h3>{{ $lowCount }}</h3>
                        <p>Stock faible</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon red"><i class="fa fa-times-circle"></i></div>
                    <div>
                        <h3>{{ $outOfStock }}</h3>
                        <p>En rupture</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- LOW STOCK ALERT --}}
        @if($lowStocks->count() > 0)
        <div class="stock-alert mb-4">
            <div class="stock-alert-icon"><i class="fa fa-exclamation-triangle"></i></div>
            <div class="stock-alert-content">
                <strong>Attention !</strong> {{ $lowStocks->count() }} produit(s) ont un stock faible ou sont en rupture :
                <span class="stock-alert-items">
                    @foreach($lowStocks->take(5) as $ls)
                        <span class="stock-alert-tag">{{ $ls->name }} ({{ $ls->quantity }})</span>
                    @endforeach
                    @if($lowStocks->count() > 5)
                        <span class="text-muted">+{{ $lowStocks->count() - 5 }} autre(s)</span>
                    @endif
                </span>
            </div>
        </div>
        @endif

        {{-- PRODUCTS TABLE --}}
        <div class="beauty-card mb-4">
            <div class="beauty-card-header">
                <h4><i class="fa fa-list mr-2" style="color:var(--primary);"></i>Tous les produits en stock</h4>
                <span class="badge badge-secondary" style="font-size:13px;">{{ $totalProducts }} produit(s)</span>
            </div>
            <div class="beauty-card-body">
                @if($stocks->isEmpty())
                <div class="beauty-empty">
                    <div class="beauty-empty-icon"><i class="fa fa-cubes"></i></div>
                    <p>Aucun produit en stock</p>
                    <a href="{{ route('admin.stocks.create') }}" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Ajouter un produit</a>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Catégorie</th>
                                <th class="text-center">Quantité</th>
                                <th class="text-center">Seuil d'alerte</th>
                                <th class="text-center">Statut</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                            <tr>
                                <td>
                                    <div class="stock-product-cell">
                                        <div class="stock-product-icon {{ $stock->quantity <= 0 ? 'stock-icon-danger' : ($stock->quantity <= $stock->alert_threshold ? 'stock-icon-warning' : 'stock-icon-ok') }}">
                                            <i class="fa fa-cube"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $stock->name }}</strong>
                                            @if($stock->quantity <= 0)
                                                <br><small class="text-danger"><i class="fa fa-warning mr-1"></i>Réapprovisionnement urgent</small>
                                            @elseif($stock->quantity <= $stock->alert_threshold)
                                                <br><small class="text-warning"><i class="fa fa-info-circle mr-1"></i>À commander bientôt</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($stock->category)
                                        <span class="badge badge-info">{{ $stock->category }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="stock-quantity {{ $stock->quantity <= 0 ? 'stock-qty-danger' : ($stock->quantity <= $stock->alert_threshold ? 'stock-qty-warning' : 'stock-qty-ok') }}">
                                        {{ $stock->quantity }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="stock-threshold">{{ $stock->alert_threshold }}</span>
                                </td>
                                <td class="text-center">
                                    @if($stock->quantity <= 0)
                                        <span class="badge badge-danger"><i class="fa fa-times mr-1"></i>Rupture</span>
                                    @elseif($stock->quantity <= $stock->alert_threshold)
                                        <span class="badge badge-warning"><i class="fa fa-exclamation mr-1"></i>Faible</span>
                                    @else
                                        <span class="badge badge-success"><i class="fa fa-check mr-1"></i>OK</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.stocks.edit', $stock) }}" class="btn btn-sm btn-primary" title="Modifier"><i class="fa fa-pencil"></i></a>
                                    <form action="{{ route('admin.stocks.destroy', $stock) }}" method="POST" class="d-inline confirm-delete" data-confirm-message="Supprimer le produit « {{ $stock->name }} » ?">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Supprimer"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            @if(method_exists($stocks, 'hasPages') && $stocks->hasPages())
            <div class="beauty-card-footer">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="text-muted small">Affichage de {{ $stocks->firstItem() }} à {{ $stocks->lastItem() }} sur {{ $stocks->total() }} produits</div>
                    <div>{{ $stocks->links() }}</div>
                </div>
            </div>
            @endif
        </div>

    </div>
</div>

<style>
/* Stock Alert Banner */
.stock-alert {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 18px 24px;
    border-radius: 16px;
    background: linear-gradient(135deg, #fff8e1, #fff3cd);
    border: 1px solid #ffe082;
    animation: stockAlertPulse 2s ease-in-out;
}
.stock-alert-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(245, 166, 35, 0.15);
    color: #F5A623;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.stock-alert-content {
    font-size: 14px;
    color: #856404;
    line-height: 1.6;
}
.stock-alert-items {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 8px;
}
.stock-alert-tag {
    display: inline-block;
    padding: 3px 12px;
    border-radius: 20px;
    background: rgba(245, 166, 35, 0.15);
    color: #856404;
    font-size: 12px;
    font-weight: 600;
}

@keyframes stockAlertPulse {
    0% { opacity: 0; transform: translateY(-10px); }
    100% { opacity: 1; transform: translateY(0); }
}

/* Product Cell */
.stock-product-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}
.stock-product-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.stock-icon-ok { background: #E8F5E9; color: #2D8B61; }
.stock-icon-warning { background: #FFF3D6; color: var(--accent); }
.stock-icon-danger { background: #FFE8EC; color: #E74C5F; }

/* Quantity Display */
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

/* Threshold */
.stock-threshold {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 8px;
    background: #f0f0f0;
    color: #666;
    font-size: 14px;
    font-weight: 500;
}

/* Stats red variant */
.beauty-stat-icon.red { background: #FFE8EC; color: #E74C5F; }

/* ─── DARK MODE ─── */
.dark-theme .stock-alert {
    background: linear-gradient(135deg, #3a3520, #332d1a);
    border-color: #665c30;
}
.dark-theme .stock-alert-icon { background: rgba(245, 166, 35, 0.1); }
.dark-theme .stock-alert-content { color: #ffd666; }
.dark-theme .stock-alert-tag { background: rgba(245, 166, 35, 0.1); color: #ffd666; }

.dark-theme .stock-icon-ok { background: rgba(45, 139, 97, 0.15); color: #5cdb95; }
.dark-theme .stock-icon-warning { background: rgba(212, 175, 55, 0.15); color: #f0d060; }
.dark-theme .stock-icon-danger { background: rgba(231, 76, 95, 0.15); color: #f08090; }

.dark-theme .stock-qty-ok { background: rgba(45, 139, 97, 0.15); color: #5cdb95; }
.dark-theme .stock-qty-warning { background: rgba(212, 175, 55, 0.15); color: #f0d060; }
.dark-theme .stock-qty-danger { background: rgba(231, 76, 95, 0.15); color: #f08090; }

.dark-theme .stock-threshold { background: #333355; color: #aaa; }

@media (max-width: 768px) {
    .stock-alert { flex-direction: column; gap: 10px; }
    .stock-product-cell { flex-direction: column; align-items: flex-start; gap: 6px; }
}
</style>
@endsection
