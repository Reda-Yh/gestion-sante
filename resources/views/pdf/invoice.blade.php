<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h1 { text-align: center; }
    </style>
</head>
<body>

<h1>Facture</h1>

<p><strong>Patient :</strong> {{ $invoice->patient->name }}</p>
<p><strong>Date RDV :</strong> {{ $invoice->appointment->appointment_at }}</p>
<p><strong>Montant :</strong> {{ $invoice->amount }} €</p>
<p><strong>Statut :</strong> {{ $invoice->status }}</p>

</body>
</html>
