{{--
    Vue : Page d'erreur 503 - Service indisponible
    Description : Page affichée lorsque l'application est en mode maintenance ou temporairement indisponible.
--}}
@extends('layouts.error')

@section('title', 'Service indisponible')

@section('code', '503')

@section('message')
    {{ $exception->getMessage() ?: "Le service est temporairement indisponible. Veuillez réessayer plus tard." }}
@endsection
