{{--
    Vue : Page d'erreur 404 - Page non trouvée
    Description : Page affichée lorsque l'URL demandée ne correspond à aucune route ou ressource existante.
--}}
@extends('layouts.error')

@section('title', 'Page non trouvée')

@section('code', '404')

@section('message')
    {{ $exception->getMessage() ?: "Désolé, la page que vous recherchez est introuvable." }}
@endsection
