<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dossier médical</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        .section {
            margin-bottom: 18px;
        }

        .label {
            font-weight: bold;
        }

        .box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 6px;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>

<h1>Dossier médical</h1>

<div class="section">
    <span class="label">Patient :</span> {{ $record->patient->name }}<br>
    <span class="label">Médecin :</span> {{ $record->medecin->name }}<br>
    <span class="label">Date du rendez-vous :</span>
    {{ $record->appointment->appointment_at }}
</div>

<div class="section">
    <span class="label">Diagnostic :</span>
    <div class="box">
        {{ $record->diagnostic }}
    </div>
</div>

@if($record->traitement)
<div class="section">
    <span class="label">Traitement :</span>
    <div class="box">
        {{ $record->traitement }}
    </div>
</div>
@endif

@if($record->notes)
<div class="section">
    <span class="label">Notes :</span>
    <div class="box">
        {{ $record->notes }}
    </div>
</div>
@endif

<div class="footer">
    Document médical confidentiel – Généré le {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>
