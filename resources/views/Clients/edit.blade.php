@extends('master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Modifier Client</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('clients.update', $client) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>Nom *</label>
                                <input type="text" name="name" class="form-control" value="{{ $client->name }}" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $client->email }}">
                            </div>
                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $client->phone }}">
                            </div>
                            <button class="btn btn-warning mt-3">Mettre à jour</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
