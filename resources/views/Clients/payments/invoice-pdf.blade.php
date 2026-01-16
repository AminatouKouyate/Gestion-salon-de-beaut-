<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #e91e63; margin: 0; }
        .invoice-info { margin-bottom: 20px; }
        .invoice-info table { width: 100%; }
        .client-info, .salon-info { width: 48%; vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 10px; }
        .items-table th { background: #e91e63; color: white; }
        .total { text-align: right; font-size: 16px; font-weight: bold; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; }
        .status { padding: 5px 10px; border-radius: 3px; display: inline-block; }
        .status-completed { background: #4caf50; color: white; }
        .status-pending { background: #ff9800; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Salon de Beauté</h1>
        <p>Facture</p>
    </div>
    
    <div class="invoice-info">
        <table>
            <tr>
                <td class="salon-info">
                    <strong>Salon de Beauté</strong><br>
                    123 Rue du Salon<br>
                    75001 Paris<br>
                    Tél: 01 23 45 67 89
                </td>
                <td class="client-info" style="text-align: right;">
                    <strong>Facturé à:</strong><br>
                    {{ $payment->appointment->client->name }}<br>
                    {{ $payment->appointment->client->email }}<br>
                    {{ $payment->appointment->client->phone ?? '' }}
                </td>
            </tr>
        </table>
    </div>
    
    <p><strong>Facture N°:</strong> {{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
    <p><strong>Date:</strong> {{ $payment->created_at->format('d/m/Y') }}</p>
    <p><strong>Statut:</strong> 
        <span class="status {{ $payment->status === 'completed' ? 'status-completed' : 'status-pending' }}">
            {{ $payment->status === 'completed' ? 'Payé' : 'En attente' }}
        </span>
    </p>
    
    <table class="items-table">
        <thead>
            <tr>
                <th>Service</th>
                <th>Date du RDV</th>
                <th>Employé</th>
                <th>Prix</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $payment->appointment->service->name }}</td>
                <td>{{ $payment->appointment->date->format('d/m/Y') }} à {{ $payment->appointment->time }}</td>
                <td>{{ $payment->appointment->employee->name ?? 'Non assigné' }}</td>
                <td style="text-align: right;">{{ number_format($payment->amount, 2, ',', ' ') }} FCFA</td>
            </tr>
        </tbody>
    </table>
    
    <p class="total">Total: {{ number_format($payment->amount, 2, ',', ' ') }} FCFA</p>
    
    <p><strong>Méthode de paiement:</strong> 
        @switch($payment->method)
            @case('stripe') Carte bancaire (Stripe) @break
            @case('paypal') PayPal @break
            @case('cash') Espèces @break
            @case('salon') Paiement au salon @break
            @default {{ $payment->method }}
        @endswitch
    </p>
    
    <div class="footer">
        <p>Merci de votre confiance !</p>
        <p>Salon de Beauté - SIRET: XXX XXX XXX XXXXX</p>
    </div>
</body>
</html>
