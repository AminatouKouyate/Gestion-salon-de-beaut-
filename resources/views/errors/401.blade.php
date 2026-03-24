{{--
    Vue : Page d'erreur 401 - Non autorisé
    Description : Page affichée lorsqu'un utilisateur non authentifié tente d'accéder à une ressource protégée.
--}}
@extends('layouts.error')

@section('title', 'Non autorisé')

@section('code', '401')

@section('message')
    {{ $exception->getMessage() ?: "Vous devez vous connecter pour accéder à cette page." }}
@endsection
