<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Certificat de Travail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            margin: 0;
            text-transform: uppercase;
        }
        
        .header p {
            margin: 5px 0;
            font-size: 16px;
        }
        
        .content {
            margin: 30px 0;
        }
        
        .content p {
            margin: 10px 0;
            text-align: justify;
        }
        
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        
        .signature-place {
            font-style: italic;
            margin-bottom: 60px;
        }
        
        .signature-name {
            font-weight: bold;
            margin-top: 40px;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        .stamp {
            position: absolute;
            right: 50px;
            top: 150px;
            border: 2px solid #000;
            padding: 10px;
            text-align: center;
            width: 150px;
        }
        
        .stamp-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .stamp-text {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'TPT-H ERP') }}</h1>
        <p>{{ config('company.address', 'Adresse de l\'entreprise') }}</p>
        <p>Tél: {{ config('company.phone', '') }} | Email: {{ config('company.email', '') }}</p>
    </div>
    
    <div class="stamp">
        <div class="stamp-title">CACHET</div>
        <div class="stamp-text">Entreprise</div>
        <div class="stamp-text">Agissant</div>
        <div class="stamp-text">Sous</div>
        <div class="stamp-text">Signature</div>
    </div>
    
    <div class="content">
        <h2 style="text-align: center; text-decoration: underline;">CERTIFICAT DE TRAVAIL</h2>
        <p style="text-align: center; margin-bottom: 30px;">N°: CT-{{ date('Y') }}-{{ str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}</p>
        
        <p>Je soussigné(e), {{ config('company.representative', 'Représentant de l\'entreprise') }}, certifie par la présente que :</p>
        
        <p><strong>{{ $employee->full_name }}</strong>, de nationalité {{ $employee->nationality ?? 'Congolaise' }}, 
        né(e) le {{ $employee->birth_date ? $employee->birth_date->format('d/m/Y') : 'N/A' }} à {{ $employee->birth_place ?? 'N/A' }},
        demeurant à {{ $employee->address ?? 'N/A' }}, matricule {{ $employee->matricule ?? 'N/A' }},
        a été employé(e) dans notre entreprise en qualité de <strong>{{ $employee->currentPosition->title ?? 'N/A' }}</strong>
        du {{ $employee->date_embauche ? $employee->date_embauche->format('d/m/Y') : 'N/A' }}
        {{ $end_date ? 'au ' . $end_date->format('d/m/Y') : 'à ce jour' }}.</p>
        
        <p>Son comportement a été {{ $reason === 'licenciement' ? 'correct jusqu\'à son licenciement' : 'exemplaire' }} 
        et ses rapports avec la hiérarchie et ses collègues ont toujours été excellents.</p>
        
        @if($additional_info)
        <p>{{ $additional_info }}</p>
        @endif
        
        <p>Ce certificat lui est délivré à sa demande pour servir et valoir ce que de droit.</p>
    </div>
    
    <div class="signature">
        <p class="signature-place">Fait à {{ config('company.city', 'Brazzaville') }}, le {{ $generated_date->format('d/m/Y') }}</p>
        <p class="signature-name">{{ config('company.representative', 'Représentant de l\'entreprise') }}</p>
        <p>{{ config('company.representative_title', 'Titre du représentant') }}</p>
    </div>
    
    <div class="footer">
        <p>{{ config('app.name', 'TPT-H ERP') }} - Certificat de Travail - Document officiel</p>
    </div>
</body>
</html>