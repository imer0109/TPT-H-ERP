<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PV d'Inventaire - {{ $inventory->reference }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            margin: 5px 0;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h2 {
            font-size: 14px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-danger {
            color: #dc3545;
        }
        .text-success {
            color: #28a745;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            border-top: 1px solid #333;
            padding-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PROCÈS-VERBAL D'INVENTAIRE</h1>
        <p>Référence: {{ $inventory->reference }}</p>
        <p>Date: {{ $inventory->date->format('d/m/Y') }}</p>
    </div>
    
    <div class="info-section">
        <h2>Informations Générales</h2>
        <table>
            <tr>
                <th style="width: 30%">Référence</th>
                <td>{{ $inventory->reference }}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{ $inventory->date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Dépôt</th>
                <td>{{ $inventory->warehouse->nom }}</td>
            </tr>
            <tr>
                <th>Statut</th>
                <td>
                    @if($inventory->status == 'en_cours')
                        En cours
                    @elseif($inventory->status == 'valide')
                        Validé
                    @endif
                </td>
            </tr>
            <tr>
                <th>Notes</th>
                <td>{{ $inventory->notes ?? 'Aucune note' }}</td>
            </tr>
        </table>
    </div>
    
    <div class="info-section">
        <h2>Informations Complémentaires</h2>
        <table>
            <tr>
                <th style="width: 30%">Créé par</th>
                <td>{{ $inventory->createdBy->name }}</td>
            </tr>
            <tr>
                <th>Date de création</th>
                <td>{{ $inventory->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Validé par</th>
                <td>{{ $inventory->validatedBy->name ?? 'Non validé' }}</td>
            </tr>
            <tr>
                <th>Date de validation</th>
                <td>{{ $inventory->validated_at ? $inventory->validated_at->format('d/m/Y H:i') : 'Non validé' }}</td>
            </tr>
            <tr>
                <th>Nombre de produits</th>
                <td>{{ $inventory->items->count() }}</td>
            </tr>
        </table>
    </div>
    
    <div class="info-section">
        <h2>Détails de l'Inventaire</h2>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Référence</th>
                    <th>Stock Théorique</th>
                    <th>Stock Réel</th>
                    <th>Différence</th>
                    <th>Valeur Différence</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalDifference = 0;
                    $totalDifferenceValue = 0;
                @endphp
                
                @foreach($inventory->items as $item)
                    @php
                        $differenceValue = $item->difference !== null ? $item->difference * $item->product->prix_achat : 0;
                        $totalDifference += $item->difference ?? 0;
                        $totalDifferenceValue += $differenceValue;
                    @endphp
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->product->reference }}</td>
                        <td class="text-right">{{ number_format($item->theoretical_quantity, 2) }}</td>
                        <td class="text-right">
                            {{ $item->actual_quantity !== null ? number_format($item->actual_quantity, 2) : '-' }}
                        </td>
                        <td class="text-right">
                            @if($item->difference !== null)
                                <span class="{{ $item->difference < 0 ? 'text-danger' : ($item->difference > 0 ? 'text-success' : '') }}">
                                    {{ number_format($item->difference, 2) }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">
                            @if($item->difference !== null)
                                <span class="{{ $differenceValue < 0 ? 'text-danger' : ($differenceValue > 0 ? 'text-success' : '') }}">
                                    {{ number_format($differenceValue, 2) }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">Total des Différences:</th>
                    <th class="text-right">
                        <span class="{{ $totalDifference < 0 ? 'text-danger' : ($totalDifference > 0 ? 'text-success' : '') }}">
                            {{ number_format($totalDifference, 2) }}
                        </span>
                    </th>
                    <th class="text-right">
                        <span class="{{ $totalDifferenceValue < 0 ? 'text-danger' : ($totalDifferenceValue > 0 ? 'text-success' : '') }}">
                            {{ number_format($totalDifferenceValue, 2) }}
                        </span>
                    </th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="signature-section">
        <div class="signature-box">
            <p>Responsable du dépôt</p>
            <p>Nom: ______________________</p>
            <p>Signature: ________________</p>
        </div>
        <div class="signature-box">
            <p>Validé par</p>
            <p>Nom: {{ $inventory->validatedBy->name ?? '______________________' }}</p>
            <p>Signature: ________________</p>
        </div>
    </div>
    
    <div class="footer">
        <p>Document généré le {{ date('d/m/Y H:i') }} | TPT-H ERP</p>
    </div>
</body>
</html>