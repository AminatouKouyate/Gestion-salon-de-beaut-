{{--
    Vue : Layout des pages d'erreur
    Description : Template de base pour les pages d'erreur HTTP (401, 403, 404, 419, 429, 500, 503) avec affichage du code d'erreur et du message.
--}}
@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Error') }}</div>

                    <div class="card-body">
                        <h1>Oops! An Error Occurred</h1>
                        <p>The server returned a "500 Internal Server Error".</p>
                        <p>Something is broken. Please let us know what you were doing when this error occurred. We will fix it as soon as possible. Sorry for any inconvenience caused.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
