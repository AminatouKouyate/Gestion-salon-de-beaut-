{{--
    Vue : Email de notification - Paiement simulé
    Description : Template d'email envoyé à l'administrateur lorsqu'un paiement est simulé par un client, avec les détails du paiement et du rendez-vous.
--}}
@component('mail::message')
# Nouveau paiement simulé

Un paiement simulé a été enregistré dans l'application.

**ID paiement:** {{ $payment->id }}

**Client:** {{ $payment->client->name ?? '�' }} (ID: {{ $payment->client_id }})

**Montant:** {{ $payment->amount }} FCFA

**Rendez-vous:** {{ $payment->appointment_id }}

@component('mail::button', ['url' => $url])
Voir le paiement
@endcomponent

Ceci est une notification automatique.

Merci,
L'équipe
@endcomponent
