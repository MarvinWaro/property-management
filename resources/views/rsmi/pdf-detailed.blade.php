<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>RSMI Detailed Report</title>
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
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            margin: 2px 0;
            font-size: 10px;
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

        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .page-break {
            page-break-after: always;
        }

        @page {
            size: legal landscape;
            margin: 0.5in;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DETAILED REPORT OF SUPPLIES AND MATERIALS ISSUED BY ITEM</h1>
        <p>{{ $entityName }}</p>
        <p>Fund Cluster: {{ $fundCluster }}</p>
        <p>Period: {{ $startDate->format('F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%">Stock No.</th>
                <th style="width: 30%">Item Description</th>
                <th style="width: 10%">Unit</th>
                <th style="width: 10%">Total Quantity<br>Issued</th>
                <th style="width: 15%">Average<br>Unit Cost</th>
                <th style="width: 15%">Total Cost</th>
                <th style="width: 10%">Category</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotal = 0;
                $totalQuantity = 0;
            @endphp

            @foreach($reportData as $supplyData)
                @php
                    $grandTotal += $supplyData['total_cost'];
                    $totalQuantity += $supplyData['total_quantity'];
                @endphp
                <tr>
                    <td class="center">{{ $supplyData['stock_no'] }}</td>
                    <td>{{ $supplyData['item_name'] }}</td>
                    <td class="center">{{ $supplyData['unit'] }}</td>
                    <td class="center">{{ number_format($supplyData['total_quantity']) }}</td>
                    <td class="right">₱{{ number_format($supplyData['average_unit_cost'], 2) }}</td>
                    <td class="right">₱{{ number_format($supplyData['total_cost'], 2) }}</td>
                    <td class="center">{{ $supplyData['category'] ?? 'N/A' }}</td>
                </tr>
            @endforeach

            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="3" class="right">GRAND TOTAL</td>
                <td class="center">{{ number_format($totalQuantity) }}</td>
                <td></td>
                <td class="right">₱{{ number_format($grandTotal, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 40px; font-size: 10px;">
        <p><strong>Summary:</strong></p>
        <p>Total Number of Items: {{ $reportData->count() }}</p>
        <p>Total Quantity Issued: {{ number_format($totalQuantity) }}</p>
        <p>Total Amount: ₱{{ number_format($grandTotal, 2) }}</p>
    </div>

    <div style="margin-top: 60px;">
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; width: 50%; text-align: center;">
                    <div style="border-bottom: 1px solid #000; width: 250px; margin: 0 auto;"></div>
                    <div style="margin-top: 5px;">Prepared by:</div>
                    <div style="margin-top: 20px; font-weight: bold;">Supply Officer</div>
                </td>
                <td style="border: none; width: 50%; text-align: center;">
                    <div style="border-bottom: 1px solid #000; width: 250px; margin: 0 auto;"></div>
                    <div style="margin-top: 5px;">Noted by:</div>
                    <div style="margin-top: 20px; font-weight: bold;">Administrative Officer</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
