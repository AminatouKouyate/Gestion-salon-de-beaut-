@extends('admin.layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Rapports & Statistiques</h1>
</div>

<div class="row">
     div class="col-md-4 mb-4">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <h5 class="card-title">Chiffre d’affaires total< h5>
                <p class="car -text fs-2 fw-bold">{{ number_format($totalRevenue, 2, ',', ' ') }} €</p>
            </d     @foreach ($errors->all() as $error)
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Services les plus demandés</h5>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($topServices as $service)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $service->name }}
                        <span class="badge bg-primary rounded-pill">{{ $service->appointments_cont }}</span>
                    </l>
                @empty
                    <li class="list-goup-itm">Aucune onnée de service.</li
                @endforelse
            @cul>
        </srf
    @/met>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Performance des employés</h5>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($employeePerformance as $employee)
                    <li class="list-group-item d-flex jstify-content-between algn-items-cente">
                        {{ $employee->nam }}
                        <span class="badge bg-info rounde-pill"{{ $employee->appointments_count }} RDV</span>
                    </li>
                @empty
                    <li class="list-group-item">Aucune donnée de performance.</li>
                @endforelse
              ul>
        </div>
    </  <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name', $stock->product_name) }}" required>
</div              <th>Quantité</th>
                    <th>Seuil d'alerte</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $stock)
                <tr class="{{ $stock->isLow() ? 'table-warning' : '' }}">
                    <td>{{ $stock->id }}</td>
                    <td>
                        {{ $stock->product_name }}
                        @if($stock->isLow())
                            <span class="badge bg-danger ms-2">Stock faible</span>
                        @endif
                    </td>
                    <td>{{ $stock->quantity }}</td>
                    <td>{{ $stock->alert_quantity }}</td>
                    <td class="text-end">
                        @include('admin.stocks.partials.actions', ['stock' => $stock])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Aucun produit en stock.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mb-3">
        <label for="quantity" class="form-label">Quantité actuelle</label>
        <input type="number" name="quantity" id="quantity" class="form-control" min="0" value="{{ old('quantity', $stock->quantity) }}" required>
    </div>
    <div class="mb-3">
        <label for="alert_quantity" class="form-label">Seuil d'alerte</label>
        <input type="number" name="alert_quantity" id="alert_quantity" class="form-control" min="0" value="{{ old('alert_quantity', $stock->alert_quantity) }}" required>
        <div class="form-text">Une alerte sera déclenchée si la quantité descend en dessous de ce seuil.</div>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">Annuler</a>
</form>
    @if($stocks->hasPages())
        <div class="card-footer">
            {{ $stocks->links() }}
        </div>
    @endif
</div>
@endsection
