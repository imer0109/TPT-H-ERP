<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche de Paie - {{ $payslip->reference }}</title>
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
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #000;
        }
        
        .header p {
            margin: 5px 0;
            font-size: 10px;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-section h2 {
            font-size: 14px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .info-table td {
            padding: 4px 8px;
            border: 1px solid #ddd;
        }
        
        .info-table td.label {
            font-weight: bold;
            width: 40%;
            background-color: #f9f9f9;
        }
        
        .earnings-table, .deductions-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .earnings-table th, .deductions-table th,
        .earnings-table td, .deductions-table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        
        .earnings-table th, .deductions-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .earnings-table td.amount, .deductions-table td.amount {
            text-align: right;
        }
        
        .totals-table {
            width: 50%;
            float: right;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .totals-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        .totals-table .total-label {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        
        .net-total {
            background-color: #e8f5e8;
            font-weight: bold;
            font-size: 14px;
        }
        
        .signature-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .signature-box {
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            height: 60px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'TPT-H ERP') }}</h1>
        <p>FICHE DE PAIE</p>
        <p>Référence: {{ $payslip->reference }}</p>
    </div>
    
    <div class="info-section">
        <div class="info-grid">
            <div>
                <h2>Informations Employé</h2>
                <table class="info-table">
                    <tr>
                        <td class="label">Nom complet:</td>
                        <td>{{ $payslip->employee->prenom }} {{ $payslip->employee->nom }}</td>
                    </tr>
                    <tr>
                        <td class="label">Matricule:</td>
                        <td>{{ $payslip->employee->matricule }}</td>
                    </tr>
                    <tr>
                        <td class="label">Poste:</td>
                        <td>{{ $payslip->employee->currentPosition->title ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date d'embauche:</td>
                        <td>{{ $payslip->employee->date_embauche ? $payslip->employee->date_embauche->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            
            <div>
                <h2>Période & Méthode de Paiement</h2>
                <table class="info-table">
                    <tr>
                        <td class="label">Période:</td>
                        <td>{{ $payslip->period_start->format('d/m/Y') }} - {{ $payslip->period_end->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date de génération:</td>
                        <td>{{ $payslip->created_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Méthode de paiement:</td>
                        <td>
                            @if($payslip->payment_method == 'cash')
                                Espèces
                            @elseif($payslip->payment_method == 'bank_transfer')
                                Virement Bancaire
                            @elseif($payslip->payment_method == 'mobile_money')
                                Mobile Money
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Statut:</td>
                        <td>
                            @if($payslip->status == 'draft')
                                Brouillon
                            @elseif($payslip->status == 'validated')
                                Validé
                            @elseif($payslip->status == 'paid')
                                Payé
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="info-section">
        <h2>Détail des Rémunérations</h2>
        <table class="earnings-table">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Montant (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Salaire de Base</td>
                    <td class="amount">{{ number_format($payslip->base_salary, 0, ',', ' ') }}</td>
                </tr>
                @if($payslip->overtime_hours > 0)
                <tr>
                    <td>Heures Supplémentaires ({{ $payslip->overtime_hours }}h × {{ number_format($payslip->overtime_rate,0,',',' ') }})</td>
                    <td class="amount">{{ number_format($payslip->overtime_hours * $payslip->overtime_rate, 0, ',', ' ') }}</td>
                </tr>
                @endif
                @if($payslip->transport_allowance > 0)
                <tr>
                    <td>Allocation Transport</td>
                    <td class="amount">{{ number_format($payslip->transport_allowance, 0, ',', ' ') }}</td>
                </tr>
                @endif
                @if($payslip->housing_allowance > 0)
                <tr>
                    <td>Allocation Logement</td>
                    <td class="amount">{{ number_format($payslip->housing_allowance, 0, ',', ' ') }}</td>
                </tr>
                @endif
                @if($payslip->meal_allowance > 0)
                <tr>
                    <td>Allocation Repas</td>
                    <td class="amount">{{ number_format($payslip->meal_allowance, 0, ',', ' ') }}</td>
                </tr>
                @endif
                @if($payslip->performance_bonus > 0)
                <tr>
                    <td>Prime de Performance</td>
                    <td class="amount">{{ number_format($payslip->performance_bonus, 0, ',', ' ') }}</td>
                </tr>
                @endif
                @if($payslip->other_allowances > 0)
                <tr>
                    <td>{{ $payslip->allowances_description ?: 'Autres Allocations' }}</td>
                    <td class="amount">{{ number_format($payslip->other_allowances, 0, ',', ' ') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <div class="info-section">
        <h2>Déductions</h2>
        <table class="deductions-table">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Montant (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                @if($payslip->social_security > 0)
                <tr>
                    <td>Cotisation Sécurité Sociale</td>
                    <td class="amount">{{ number_format($payslip->social_security,0,',',' ') }}</td>
                </tr>
                @endif
                @if($payslip->income_tax > 0)
                <tr>
                    <td>Impôt sur le Revenu</td>
                    <td class="amount">{{ number_format($payslip->income_tax,0,',',' ') }}</td>
                </tr>
                @endif
                @if($payslip->advance_deduction > 0)
                <tr>
                    <td>Avance sur Salaire</td>
                    <td class="amount">{{ number_format($payslip->advance_deduction,0,',',' ') }}</td>
                </tr>
                @endif
                @if($payslip->loan_deduction > 0)
                <tr>
                    <td>Remboursement Prêt</td>
                    <td class="amount">{{ number_format($payslip->loan_deduction,0,',',' ') }}</td>
                </tr>
                @endif
                @if($payslip->other_deductions > 0)
                <tr>
                    <td>{{ $payslip->deductions_description ?: 'Autres Déductions' }}</td>
                    <td class="amount">{{ number_format($payslip->other_deductions,0,',',' ') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <table class="totals-table">
        <tr>
            <td class="total-label">TOTAL BRUT</td>
            <td class="amount">{{ number_format($payslip->gross_salary,0,',',' ') }} FCFA</td>
        </tr>
        <tr>
            <td class="total-label">TOTAL DÉDUCTIONS</td>
            <td class="amount">{{ number_format($payslip->total_deductions,0,',',' ') }} FCFA</td>
        </tr>
        <tr class="net-total">
            <td>SALAIRE NET À PAYER</td>
            <td>{{ number_format($payslip->net_salary,0,',',' ') }} FCFA</td>
        </tr>
    </table>
    
    @if($payslip->notes)
    <div class="info-section">
        <h2>Notes/Commentaires</h2>
        <p>{{ $payslip->notes }}</p>
    </div>
    @endif
    
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Signature de l'Employé</p>
            <p>{{ $payslip->employee->prenom }} {{ $payslip->employee->nom }}</p>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Signature de l'Employeur</p>
            <p>Direction des Ressources Humaines</p>
        </div>
    </div>
</body>
</html>