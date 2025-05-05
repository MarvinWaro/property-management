<!-- stock-cards/pdf.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Card - {{ $supply->item_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .info-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-section {
            width: 48%;
        }
        .info-group {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
        .receipt {
            color: green;
        }
        .issue {
            color: red;
        }
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .signature-section {
            width: 45%;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>Republic of the Philippines</div>
        <div class="title">COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII</div>
        <div class="subtitle">STOCK CARD</div>
        <div>Appendix 58</div>
    </div>

    <div class="info-container">
        <div class="info-section">
            <div class="info-group">
                <span class="info-label">Entity Name:</span> COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII
            </div>
            <div class="info-group">
                <span class="info-label">Item:</span> {{ $supply->item_name }}
            </div>
            <div class="info-group">
                <span class="info-label">Description:</span> {{ $supply->description }}
            </div>
            <div class="info-group">
                <span class="info-label">Unit of Measurement:</span> {{ $supply->unit_of_measurement }}
            </div>
        </div>

        <div class="info-section">
            <div class="info-group">
                <span class="info-label">Fund Cluster:</span> {{ $fundCluster }}
            </div>
            <div class="info-group">
                <span class="info-label">Stock No.:</span> {{ $supply->stock_no }}
            </div>
            <div class="info-group">
                <span class="info-label">Re-order Point:</span> {{ $supply->reorder_point }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Date</th>
                <th rowspan="2">Reference</th>
                <th colspan="1" class="center">Receipt</th>
                <th colspan="2" class="center">Issue</th>
                <th colspan="1" class="center">Balance</th>
                <th rowspan="2" class="center">No. of Days to Consume</th>
            </tr>
            <tr>
                <th class="center">Qty.</th>
                <th class="center">Qty.</th>
                <th class="center">Office</th>
                <th class="center">Qty.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockCardEntries as $entry)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($entry['date'])->format('m/d/Y') }}</td>
                    <td>{{ $entry['reference'] }}</td>
                    <td class="center receipt">{{ $entry['receipt_qty'] ? number_format($entry['receipt_qty']) : '' }}</td>
                    <td class="center issue">{{ $entry['issue_qty'] ? number_format($entry['issue_qty']) : '' }}</td>
                    <td>{{ $entry['issue_office'] }}</td>
                    <td class="center">{{ number_format($entry['balance_qty']) }}</td>
                    <td class="center">{{ $entry['days_to_consume'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-section">
            <div>Prepared by:</div>
            <br><br>
            <div>________________________</div>
            <div>Supply Officer</div>
        </div>

        <div class="signature-section">
            <div>Approved by:</div>
            <br><br>
            <div>________________________</div>
            <div>Regional Director</div>
        </div>
    </div>
</body>
</html>
