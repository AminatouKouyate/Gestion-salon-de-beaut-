@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Effectuer un paiement</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('client.payments.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sélectionner un rendez-vous à payer</h4>
                    </div>
                    <div class="card-body">
                        @if($unpaidAppointments->isEmpty() && !$appointment)
                            <div class="text-center py-5">
                                <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                                <p class="text-muted">Tous vos rendez-vous sont payés !</p>
                                <a href="{{ route('client.appointments.index') }}" class="btn btn-primary">
                                    Voir mes rendez-vous
                                </a>
                            </div>
                        @else
                            <form action="{{ route('client.payments.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="appointment_id">Rendez-vous</label>
                                    <select name="appointment_id" id="appointment_id" class="form-control" required>
                                        @if($appointment)
                                            <option value="{{ $appointment->id }}" selected>
                                                {{ $appointment->service->name }} - {{ $appointment->date->format('d/m/Y') }} - {{ $appointment->service->price }} FCFA
                                            </option>
                                        @else
                                            <option value="">Sélectionner un rendez-vous</option>
                                            @foreach($unpaidAppointments as $apt)
                                                <option value="{{ $apt->id }}" data-price="{{ $apt->service->price }}">
                                                    {{ $apt->service->name }} - {{ $apt->date->format('d/m/Y') }} - {{ $apt->service->price }} FCFA
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Mode de paiement</label>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="card payment-option" data-method="stripe">
                                                <div class="card-body text-center">
                                                    <input type="radio" name="method" value="stripe" id="stripe" class="d-none">
                                                    <label for="stripe" class="mb-0 cursor-pointer w-100">
                                                        <i class="fa fa-cc-stripe fa-3x text-primary mb-2"></i>
                                                        <h5>Carte bancaire</h5>
                                                        <small class="text-muted">Visa, Mastercard</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="card payment-option" data-method="paypal">
                                                <div class="card-body text-center">
                                                    <input type="radio" name="method" value="paypal" id="paypal" class="d-none">
                                                    <label for="paypal" class="mb-0 cursor-pointer w-100">
                                                        <i class="fa fa-paypal fa-3x text-info mb-2"></i>
                                                        <h5>PayPal</h5>
                                                        <small class="text-muted">Paiement sécurisé</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="card payment-option" data-method="cash">
                                                <div class="card-body text-center">
                                                    <input type="radio" name="method" value="cash" id="cash" class="d-none">
                                                    <label for="cash" class="mb-0 cursor-pointer w-100">
                                                        <i class="fa fa-money fa-3x text-success mb-2"></i>
                                                        <h5>Espèces</h5>
                                                        <small class="text-muted">Au salon</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="card payment-option" data-method="salon">
                                                <div class="card-body text-center">
                                                    <input type="radio" name="method" value="salon" id="salon" class="d-none">
                                                    <label for="salon" class="mb-0 cursor-pointer w-100">
                                                        <i class="fa fa-building fa-3x text-secondary mb-2"></i>
                                                        <h5>Au salon</h5>
                                                        <small class="text-muted">Carte ou espèces</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">
                                    <i class="fa fa-lock mr-2"></i>Procéder au paiement
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h4 class="card-title text-white mb-0">Récapitulatif</h4>
                    </div>
                    <div class="card-body">
                        <div id="payment-summary">
                            <p class="text-muted">Sélectionnez un rendez-vous</p>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h5><i class="fa fa-shield mr-2 text-success"></i>Paiement sécurisé</h5>
                        <p class="text-muted mb-0">Vos données sont protégées par un cryptage SSL 256 bits.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-option {
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid transparent;
}
.payment-option:hover {
    border-color: #007bff;
}
.payment-option.selected {
    border-color: #007bff;
    background-color: #f8f9ff;
}
.cursor-pointer {
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentOptions = document.querySelectorAll('.payment-option');

    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            paymentOptions.forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });

    const appointmentSelect = document.getElementById('appointment_id');
    const summary = document.getElementById('payment-summary');

    if (appointmentSelect) {
        appointmentSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (this.value) {
                const text = selectedOption.text;
                const parts = text.split(' - ');
                summary.innerHTML = `
                    <p><strong>Service:</strong> ${parts[0]}</p>
                    <p><strong>Date:</strong> ${parts[1]}</p>
                    <hr>
                    <p class="h4 text-primary"><strong>Total: ${parts[2]}</strong></p>
                `;
            } else {
                summary.innerHTML = '<p class="text-muted">Sélectionnez un rendez-vous</p>';
            }
        });

        if (appointmentSelect.value) {
            appointmentSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>
@endsection
