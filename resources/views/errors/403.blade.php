{{--
    Vue : Page d'erreur 403 - Accès interdit
    Description : Page affichée lorsqu'un utilisateur authentifié n'a pas les permissions nécessaires pour accéder à une ressource.
--}}
@extends('layouts.error')

@section('title', 'Accès interdit')

@section('code', '403')

@section('message')
    {{ $exception->getMessage() ?: "Désolé, vous n'avez pas la permission d'accéder à cette page." }}
@endsection
