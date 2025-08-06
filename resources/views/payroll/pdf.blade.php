<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche de Paie - {{ $payslip->reference }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-info {
            float: left;
            width: 50%;
        }
        .employee-info {
            float: right;
            width: 50%;
            text-align: right;
        }
        .clear {
            clear: both;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f5f5f5;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 50px;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FICHE DE PAIE</h1>
        <h2>{{ $payslip->period->format('F Y') }}</h2>
    </div>

    <div class="company-info">
        <h3>{{ config('app.name') }}</h3>
        <p>
            {{ config('company.address') }}<br>
            {{ config('company.postal_code') }} {{ config('company.city') }}<br>
            SIRET : {{ config('company.siret') }}
        </p>
    </div>

    <div class="employee-info">
        <h3>{{ $payslip->employee->full_name }}</h3>
        <p>
            Matricule : {{ $payslip->employee->employee_id }}<br>
            {{ $payslip->employee->address }}<br>
            {{ $payslip->employee->postal_code }} {{ $payslip->employee->city }}
        </p>
    </div>

    <div class="clear"></div>

    <table>
        <tr>
            <th>Poste</th>
            <td>{{ $payslip->employee->position->title }}</td>
            <th>Département</th>
            <td>{{ $payslip->employee->position->department }}</td>
        </tr>
        <tr>
            <th>Date d'entrée</th>
            <td>{{ $payslip->employee->hire_date->format('d/m/Y') }}</td>
            <th>Référence</th>
            <td>{{ $payslip->reference }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>Salaire de base</th>
            <td class="text-right">{{ number_format($payslip->base_salary, 2, ',', ' ') }} €</td>
            <th>Jours travaillés</th>
            <td class="text-right">{{ $payslip->worked_days }}</td>
        </tr>
        <tr>
            <th>Heures supplémentaires</th>
            <td class="text-right">{{ $payslip->overtime_hours }}</td>
            <th>Taux horaire</th>
            <td class="text-right">{{ number_format($payslip->base_salary / 151.67, 2, ',', ' ') }} €</td>
        </tr>
    </table>

    <table>
        <tr>
            <th width="60%">Description</th>
            <th width="20%" class="text-right">Base</th>
            <th width="20%" class="text-right">Montant</th>
        </tr>
        
        @foreach($payslip->earnings as $earning)
        <tr>
            <td>{{ $earning['name'] }}</td>
            <td class="text-right">
                @if($earning['calculation_type'] === 'percentage')
                    {{ number_format($payslip->base_salary, 2, ',', ' ') }} €
                @endif
            </td>
            <td class="text-right">{{ number_format($earning['amount'], 2, ',', ' ') }} €</td>
        </tr>
        @endforeach
        
        <tr class="total-row">
            <td colspan="2">Total des gains</td>
            <td class="text-right">{{ number_format($payslip->gross_salary, 2, ',', ' ') }} €</td>
        </tr>
        
        @foreach($payslip->deductions as $deduction)
        <tr>
            <td>{{ $deduction['name'] }}</td>
            <td class="text-right">
                @if($deduction['calculation_type'] === 'percentage')
                    {{ number_format($payslip->gross_salary, 2, ',', ' ') }} €
                @endif
            </td>
            <td class="text-right">{{ number_format($deduction['amount'], 2, ',', ' ') }} €</td>
        </tr>
        @endforeach
        
        <tr class="total-row">
            <td colspan="2">Total des déductions</td>
            <td class="text-right">{{ number_format($payslip->gross_salary - $payslip->net_salary, 2, ',', ' ') }} €</td>
        </tr>
        
        <tr class="total-row">
            <td colspan="2">NET À PAYER</td>
            <td class="text-right">{{ number_format