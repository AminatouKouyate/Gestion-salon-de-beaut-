{{--
    Vue : Paiement mobile (Orange Money / Wave)
    Description : Page de paiement mobile permettant au client de finaliser un paiement via Orange Money ou Wave avec saisie du numéro de téléphone.
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Confirmation du paiement {{ $method === 'orange_money' ? 'Orange Money' : 'Wave' }}</h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-{{ $method === 'orange_money' ? 'warning' : 'info' }}">
                        <h4 class="card-title text-white mb-0">
                            <i class="fa fa-mobile fa-2x mr-2"></i>
                            Paiement {{ $method === 'orange_money' ? 'Orange Money' : 'Wave' }}
                        </h4>
                    </div>
                    <div class="card-body text-center">
                        @if($paymentInfo)
                            <div class="mb-4">
                                <i class="fa fa-{{ $method === 'orange_money' ? 'mobile' : 'mobile' }} fa-5x text-{{ $method === 'orange_money' ? 'warning' : 'info' }} mb-3"></i>
                                <h3>{{ $paymentInfo['message'] ?? 'Veuillez confirmer le paiement' }}</h3>
                            </div>

                            <div class="alert alert-info">
                                <h5><strong>Détails du paiement</strong></h5>
                                <p class="mb-1"><strong>Montant:</strong> {{ number_format($payment->amount, 0, ',', ' ') }} FCFA</p>
                                <p class="mb-1"><strong>Numéro de téléphone:</strong> {{ $paymentInfo['phone_number'] ?? '�' }}</p>
                                <p class="mb-0"><strong>Transaction ID:</strong> {{ $paymentInfo['transaction_id'] ?? '�' }}</p>
                            </div>

                            @if($method === 'orange_money' && isset($paymentInfo['ussd_code']))
                                <div class="alert alert-warning">
                                    <h5><i class="fa fa-info-circle mr-2"></i>Instructions</h5>
                                    <p class="mb-2">Composez le code suivant sur votre téléphone :</p>
                                    <h3 class="text-primary">{{ $paymentInfo['ussd_code'] }}</h3>
                                    <p class="mb-0 small">Ou confirmez directement depuis l'application Orange Money</p>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <h5><i class="fa fa-info-circle mr-2"></i>Instructions</h5>
                                    <p class="mb-0">Veuillez confirmer le paiement depuis votre application {{ $method === 'orange_money' ? 'Orange Money' : 'Wave' }}</p>
                                </div>
                            @endif

                            <div class="mt-4">
                                <button type="button" class="btn btn-primary btn-lg" id="check-payment-status">
                                    <i class="fa fa-refresh mr-2"></i>Vérifier le statut du paiement
                                </button>
                                <a href="{{ route('client.payments.index') }}" class="btn btn-secondary btn-lg ml-2">
                                    <i class="fa fa-arrow-left mr-2"></i>Retour
                                </a>
                            </div>

                            <div id="payment-status-result" class="mt-3" style="display: none;"></div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle fa-2x mb-3"></i>
                                <p>Informations de paiement non disponibles. Veuillez réessayer.</p>
                                <a href="{{ route('client.payments.create', $payment->appointment) }}" class="btn btn-primary">
                                    Réessayer
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h5><i class="fa fa-info-circle mr-2 text-info"></i>Informations importantes</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fa fa-check text-success mr-2"></i>Le paiement sera automatiquement confirmé une fois validé</li>
                            <li class="mb-2"><i class="fa fa-check text-success mr-2"></i>Vous recevrez une notification de confirmation</li>
                            <li class="mb-0"><i class="fa fa-check text-success mr-2"></i>Votre facture sera disponible dans votre historique</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkButton = document.getElementById('check-payment-status');
    const resultDiv = document.getElementById('payment-status-result');
    
    if (checkButton) {
        checkButton.addEventListener('click', function() {
            const button = this;
            const originalText = button.innerHTML;
            
            button.disabled = true;
            button.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Vérification...';
            
            fetch('{{ route("client.payments.check-status", $payment->id) }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                button.disabled = false;
                button.innerHTML = originalText;
                
                if (data.status === 'paid') {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle fa-2x mb-2"></i>
                            <h5>Paiement confirmé !</h5>
                            <p>${data.message}</p>
                            <a href="{{ route('client.payments.index') }}" class="btn btn-success">
                                Voir mes paiements
                            </a>
                        </div>
                    `;
                    resultDiv.style.display = 'block';
                    
                    // Rediriger après 3 secondes
                    setTimeout(function() {
                        window.location.href = '{{ route("client.payments.index") }}';
                    }, 3000);
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fa fa-clock-o fa-2x mb-2"></i>
                            <h5>Paiement en attente</h5>
                            <p>${data.message}</p>
                        </div>
                    `;
                    resultDiv.style.display = 'block';
                }
            })
            .catch(error => {
                button.disabled = false;
                button.innerHTML = originalText;
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-triangle fa-2x mb-2"></i>
                        <h5>Erreur</h5>
                        <p>Une erreur est survenue. Veuillez réessayer.</p>
                    </div>
                `;
                resultDiv.style.display = 'block';
            });
        });
    }
    
    // Vérification automatique toutes les 10 secondes
    setInterval(function() {
        if (checkButton && !checkButton.disabled) {
            checkButton.click();
        }
    }, 10000);
});
</script>
@endsection

