{{--
    Vue : Page d'erreur 419 - Page expirée
    Description : Page affichée lorsque le jeton CSRF a expiré, invitant l'utilisateur à rafraîchir la page.
--}}
@extends('layouts.error')

@section('title', 'Page expirée')

@section('code', '419')

@section('message')
    Votre session a expiré. Veuillez rafraîchir la page et réessayer.
@endsection
