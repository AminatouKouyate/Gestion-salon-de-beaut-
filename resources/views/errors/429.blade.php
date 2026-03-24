{{--
    Vue : Page d'erreur 429 - Trop de requêtes
    Description : Page affichée lorsque l'utilisateur a dépassé la limite de requêtes (rate limiting).
--}}
@extends('layouts.error')

@section('title', 'Trop de requêtes')

@section('code', '429')

@section('message')
    Vous avez effectué trop de requêtes. Veuillez patienter quelques instants avant de réessayer.
@endsection
