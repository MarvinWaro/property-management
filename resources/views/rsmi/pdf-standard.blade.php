<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>RSMI Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 10px;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            margin: 2px 0;
        }

        .appendix {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 10px;
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }

        td {
            vertical-align: top;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .info-section {
            margin-bottom: 15px;
            font-size: 10px;
        }

        .info-section .label {
            display: inline-block;
            width: 100px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
        }

        .signature-section {
            margin-top: 40px;
            font-size: 10px;
        }

        .signature-line {
            display: inline-block;
            width: 200px;
            text-align: center;
            margin: 0 20px;
        }

        .signature-line .line {
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
            height: 30px;
        }

        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        @page {
            size: legal landscape;
            margin: 0.5in;
        }
    </style>
</head>
<body>
    <div class="appendix">Appendix 64</div>

    <div class="header">
        <h1>REPORT OF SUPPLIES AND MATERIALS ISSUED</h1>
    </div>

    <div class="info-section">
        <div><span class="label">Entity Name:</span> {{ $entityName }}</div>
        <div><span class="label">Fund Cluster:</span> {{ $fundCluster }}</div>
        <div><span class="label">Serial No.:</span> _______________</div>
        <div><span class="label">Date:</span> {{ $startDate->format('F Y') }}</div>
    </div>

    <div style="font-size: 9px; text-align: center; margin-bottom: 10px;">
        <em>To be filled up by the Supply and/or Property Division/Unit</em><br>
        <em>To be filled up by the Accounting Division/Unit</em>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 10%">RIS No.</th>
                <th rowspan="2" style="width: 15%">Responsibility<br>Center Code</th>
                <th rowspan="2" style="width: 10%">Stock No.</th>
                <th rowspan="2" style="width: 25%">Item</th>
                <th rowspan="2" style="width: 8%">Unit</th>
                <th rowspan="2" style="width: 8%">Quantity<br>Issued</th>
                <th rowspan="2" style="width: 12%">Unit Cost</th>
                <th rowspan="2" style="width: 12%">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $risData)
                @foreach($risData['items'] as $index => $item)
                    <tr>
                        <td class="center">
                            @if($index === 0)
                                {{ $risData['ris_no'] }}
                            @endif
                        </td>
                        <td class="center">
                            @if($index === 0)
                                {{ $risData['department'] }}
                            @endif
                        </td>
                        <td class="center">{{ $item['stock_no'] }}</td>
                        <td>{{ $item['item_name'] }}</td>
                        <td class="center">{{ $item['unit'] }}</td>
                        <td class="center">{{ number_format($item['quantity_issued']) }}</td>
                        <td class="right">{{ number_format($item['unit_cost'], 2) }}</td>
                        <td class="right">{{ number_format($item['total_cost'], 2) }}</td>
                    </tr>
                @endforeach
            @endforeach

            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="7" class="right">TOTAL</td>
                <td class="right">{{ number_format($reportData->sum(function($ris) {
                    return $ris['items']->sum('total_cost');
                }), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p><strong>RECAPITULATION</strong></p>
        <table style="width: 50%; margin-top: 10px;">
            <tr>
                <td style="width: 70%">Total Amount of Issuance</td>
                <td class="right">₱{{ number_format($reportData->sum(function($ris) {
                    return $ris['items']->sum('total_cost');
                }), 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="signature-section">
        <div style="margin-bottom: 40px;">
            <div class="signature-line">
                <div class="line"></div>
                <div>Prepared by:</div>
                <div style="margin-top: 5px; font-weight: bold;">Supply Officer/Property Custodian</div>
            </div>

            <div class="signature-line">
                <div class="line"></div>
                <div>Certified Correct:</div>
                <div style="margin-top: 5px; font-weight: bold;">Head, Accounting Unit/Authorized Representative</div>
            </div>
        </div>
    </div>
</body>
</html>
