@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Informations Client</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Nom :</strong> {{ $client->name }}</p>
                        <p><strong>Email :</strong> {{ $client->email }}</p>
                        <p><strong>Téléphone :</strong> {{ $client->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
