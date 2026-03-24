{{--
    Vue : Page d'erreur 500 - Erreur serveur
    Description : Page affichée lors d'une erreur interne du serveur, invitant l'utilisateur à réessayer plus tard.
--}}
@extends('layouts.error')

@section('title', 'Erreur serveur')

@section('code', '500')

@section('message')
    Désolé, une erreur s'est produite sur le serveur. Veuillez réessayer plus tard.
@endsection
