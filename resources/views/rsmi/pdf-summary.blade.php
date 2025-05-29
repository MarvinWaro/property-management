<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report of Supplies and Materials Issued - Summary</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
        }

        .container {
            padding: 20px;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 12px;
            font-weight: normal;
            margin-bottom: 20px;
        }

        .appendix {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 10px;
            font-style: italic;
        }

        /* Entity Information */
        .entity-info {
            margin-bottom: 15px;
        }

        .entity-info table {
            width: 100%;
            border: none;
        }

        .entity-info td {
            padding: 2px 0;
            font-size: 10px;
            border: none;
        }

        .entity-info .label {
            width: 150px;
            font-weight: normal;
        }

        .entity-info .value {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-left: 5px;
        }

        /* Main Table */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            font-size: 9px;
        }

        .main-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .main-table td {
            height: 20px;
        }

        .main-table .text-left {
            text-align: left;
            padding-left: 5px;
        }

        .main-table .text-right {
            text-align: right;
            padding-right: 5px;
        }

        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        /* Signature Section */
        .signatures {
            margin-top: 50px;
        }

        .signature-row {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
            height: 40px;
        }

        .signature-label {
            font-size: 10px;
            margin-bottom: 5px;
        }

        .signature-title {
            font-size: 10px;
            font-weight: bold;
            margin-top: 10px;
        }

        @page {
            size: legal landscape;
            margin: 0.5in;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="appendix">Appendix 64</div>

        <div class="header">
            <h1>REPORT OF SUPPLIES AND MATERIALS ISSUED</h1>
            <h2>(Summary by Stock Number)</h2>
        </div>

        <div class="entity-info">
            <table>
                <tr>
                    <td class="label">Entity Name:</td>
                    <td class="value" style="width: 400px;">{{ $entityName }}</td>
                    <td style="width: 150px;"></td>
                    <td class="label">Period:</td>
                    <td class="value" style="width: 150px;">{{ $startDate->format('F Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Fund Cluster:</td>
                    <td class="value" style="width: 200px;">{{ $fundCluster }}</td>
                    <td colspan="3"></td>
                </tr>
            </table>
        </div>

        <table class="main-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Stock No.</th>
                    <th style="width: 45%;">Item Description</th>
                    <th style="width: 10%;">Unit</th>
                    <th style="width: 15%;">Total Quantity<br>Issued</th>
                    <th style="width: 15%;">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotalQuantity = 0;
                    $grandTotalAmount = 0;
                @endphp

                @foreach($summaryData as $item)
                    @php
                        $grandTotalQuantity += $item['total_quantity'];
                        $grandTotalAmount += $item['total_cost'];
                    @endphp
                    <tr>
                        <td>{{ $item['stock_no'] }}</td>
                        <td class="text-left">{{ $item['item_name'] }}</td>
                        <td>{{ $item['unit'] }}</td>
                        <td>{{ number_format($item['total_quantity']) }}</td>
                        <td class="text-right">{{ number_format($item['total_cost'], 2) }}</td>
                    </tr>
                @endforeach

                <!-- Total Row -->
                <tr class="total-row">
                    <td colspan="3" style="text-align: right; padding-right: 10px;">GRAND TOTAL</td>
                    <td>{{ number_format($grandTotalQuantity) }}</td>
                    <td class="text-right">{{ number_format($grandTotalAmount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="signatures">
            <div class="signature-row">
                <div class="signature-box">
                    <div class="signature-label">Prepared by:</div>
                    <div class="signature-line"></div>
                    <div class="signature-title">Supply Officer</div>
                </div>
                <div class="signature-box">
                    <div class="signature-label">Certified Correct:</div>
                    <div class="signature-line"></div>
                    <div class="signature-title">Head, Accounting Unit</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
