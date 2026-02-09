<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bon de Commande {{ $order->code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 20px;
            margin: 0 0 10px 0;
            color: #000;
        }
        
        .header p {
            margin: 2px 0;
            color: #666;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-section h2 {
            font-size: 16px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin: 0 0 10px 0;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .info-item {
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        tfoot td {
            font-weight: bold;
        }
        
        .signature-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .signature-box {
            border-top: 1px solid #333;
            padding-top: 10px;
            text-align: center;
        }
        
        .page-footer {
            position: fixed;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BON DE COMMANDE</h1>
        <p>Code: {{ $order->code }}</p>
        <p>Date: {{ $order->date_commande->format('d/m/Y') }}</p>
    </div>

    <div class="info-section">
        <h2>INFORMATIONS FOURNISSEUR</h2>
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <span class="info-label">Raison sociale:</span> {{ $order->fournisseur->raison_sociale ?? 'N/A' }}
                </div>
                @if($order->fournisseur->adresse)
                <div class="info-item">
                    <span class="info-label">Adresse:</span> {{ $order->fournisseur->adresse }}
                </div>
                @endif
            </div>
            <div>
                @if($order->fournisseur->telephone)
                <div class="info-item">
                    <span class="info-label">Téléphone:</span> {{ $order->fournisseur->telephone }}
                </div>
                @endif
                @if($order->fournisseur->email)
                <div class="info-item">
                    <span class="info-label">Email:</span> {{ $order->fournisseur->email }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="info-section">
        <h2>INFORMATIONS COMMANDE</h2>
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <span class="info-label">Nature d'achat:</span> {{ $order->nature_achat }}
                </div>
                @if($order->delai_contractuel)
                <div class="info-item">
                    <span class="info-label">Délai contractuel:</span> {{ $order->delai_contractuel->format('d/m/Y') }}
                </div>
                @endif
            </div>
            <div>
                @if($order->conditions_paiement)
                <div class="info-item">
                    <span class="info-label">Conditions de paiement:</span> {{ $order->conditions_paiement }}
                </div>
                @endif
                @if($order->adresse_livraison)
                <div class="info-item">
                    <span class="info-label">Adresse de livraison:</span> {{ $order->adresse_livraison }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="info-section">
        <h2>ARTICLES COMMANDES</h2>
        <table>
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th>Quantité</th>
                    <th>Unité</th>
                    <th>P.U. ({{ $order->devise }})</th>
                    <th>Total ({{ $order->devise }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->designation }}</td>
                    <td style="text-align: center;">{{ $item->quantite }}</td>
                    <td style="text-align: center;">{{ $item->unite }}</td>
                    <td style="text-align: right;">{{ number_format($item->prix_unitaire, 2, ',', ' ') }}</td>
                    <td style="text-align: right;">{{ number_format($item->montant_total, 2, ',', ' ') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;">Total HT:</td>
                    <td style="text-align: right;">{{ number_format($order->montant_ht, 2, ',', ' ') }} {{ $order->devise }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right;">TVA (18%):</td>
                    <td style="text-align: right;">{{ number_format($order->montant_tva, 2, ',', ' ') }} {{ $order->devise }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: bold;">Total TTC:</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($order->montant_ttc, 2, ',', ' ') }} {{ $order->devise }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($order->notes)
    <div class="info-section">
        <h2>NOTES</h2>
        <p>{{ $order->notes }}</p>
    </div>
    @endif

    <div class="signature-section">
        <div class="signature-box">
            <p>Signature Fournisseur</p>
            <p>Date: __/__/______</p>
        </div>
        <div class="signature-box">
            <p>Signature Responsable</p>
            <p>Date: __/__/______</p>
        </div>
    </div>

    <div class="page-footer">
        Document généré le {{ date('d/m/Y H:i') }} - Page 1
    </div>
</body>
</html>