{{--
    Vue : Détails d'un client (admin)
    Description : Affiche les informations détaillées d'un client pour l'administrateur.
--}}
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
                        <p><strong>Téléphone :</strong> {{ $client->phone ?? '�' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
