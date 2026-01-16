<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $payment->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: #fff;
            padding: 40px;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .company-info h1 {
            color: #007bff;
            font-size: 28px;
            margin-bottom: 5px;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info h2 {
            color: #007bff;
            font-size: 24px;
        }
        .invoice-info p {
            margin: 5px 0;
        }
        .parties {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .party {
            width: 45%;
        }
        .party h3 {
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        .totals {
            text-align: right;
            margin-bottom: 40px;
        }
        .totals table {
            width: 300px;
            margin-left: auto;
        }
        .totals td {
            padding: 8px 12px;
        }
        .totals .total-row {
            font-size: 18px;
            font-weight: bold;
            background: #f8f9fa;
        }
        .totals .total-row td:last-child {
            color: #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-completed {
            background: #28a745;
            color: white;
        }
        .status-pending {
            background: #ffc107;
            color: #333;
        }
        @media print {
            body {
                padding: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; border-radius: 5px;">
            Imprimer
        </button>
        <a href="{{ route('client.payments.invoice.download', $payment) }}" style="padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">
            Télécharger PDF
        </a>
        <a href="{{ route('client.payments.index') }}" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">
            Retour
        </a>
    </div>

    <div class="invoice-header">
        <div class="company-info">
            <h1>🏠 Salon de Coiffure</h1>
            <p>123 Rue du Salon</p>
            <p>75001 Paris, France</p>
            <p>Tél: 01 23 45 67 89</p>
            <p>contact@salon.fr</p>
        </div>
        <div class="invoice-info">
            <h2>FACTURE</h2>
            <p><strong>N°:</strong> {{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Date:</strong> {{ $payment->created_at->format('d/m/Y') }}</p>
            <p>
                <span class="status-badge status-{{ $payment->status }}">
                    {{ $payment->status == 'completed' ? 'Payé' : 'En attente' }}
                </span>
            </p>
        </div>
    </div>

    <div class="parties">
        <div class="party">
            <h3>Facturé à</h3>
            <p><strong>{{ $payment->appointment->client->name ?? 'Client' }}</strong></p>
            <p>{{ $payment->appointment->client->email ?? '' }}</p>
            <p>{{ $payment->appointment->client->phone ?? '' }}</p>
            <p>{{ $payment->appointment->client->address ?? '' }}</p>
        </div>
        <div class="party">
            <h3>Détails du rendez-vous</h3>
            <p><strong>Date:</strong> {{ $payment->appointment->date->format('d/m/Y') }}</p>
            <p><strong>Heure:</strong> {{ $payment->appointment->time }}</p>
            @if($payment->appointment->employee)
                <p><strong>Employé:</strong> {{ $payment->appointment->employee->name }}</p>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Durée</th>
                <th style="text-align: right;">Prix</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $payment->appointment->service->name ?? 'Service' }}</strong>
                    @if($payment->appointment->service->description)
                        <br><small style="color: #666;">{{ $payment->appointment->service->description }}</small>
                    @endif
                </td>
                <td>{{ $payment->appointment->service->duration ?? 0 }} min</td>
                <td style="text-align: right;">{{ number_format($payment->amount, 2, ',', ' ') }} FCFA</td>
            </tr>
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Sous-total</td>
                <td style="text-align: right;">{{ number_format($payment->amount, 2, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td>TVA (20%)</td>
                <td style="text-align: right;">{{ number_format($payment->amount * 0.2, 2, ',', ' ') }} FCFA</td>
            </tr>
            <tr class="total-row">
                <td>Total TTC</td>
                <td style="text-align: right;">{{ number_format($payment->amount * 1.2, 2, ',', ' ') }} FCFA</td>
            </tr>
        </table>
    </div>

    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 30px;">
        <p><strong>Mode de paiement:</strong> 
            @switch($payment->method)
                @case('stripe')
                    Carte bancaire
                    @break
                @case('paypal')
                    PayPal
                    @break
                @case('cash')
                    Espèces
                    @break
                @case('salon')
                    Paiement au salon
                    @break
            @endswitch
        </p>
        @if($payment->transaction_id)
            <p><strong>Référence transaction:</strong> {{ $payment->transaction_id }}</p>
        @endif
    </div>

    <div class="footer">
        <p>Merci pour votre confiance !</p>
        <p style="margin-top: 10px; font-size: 12px;">
            Salon de Coiffure - SIRET: 123 456 789 00012 - TVA: FR 12 345678901
        </p>
    </div>
</body>
</html>
