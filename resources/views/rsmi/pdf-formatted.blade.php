<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report of Supplies and Materials Issued</title>
    <style>
        /* 1-inch margins on A4 landscape */
        @page {
            size: A4 landscape;
            margin: 0.5in !important;
        }
        @media print {
            html, body { margin: 0; padding: 0; }
        }

        /* Reset */
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.2;
        }

        /* Appendix (top-right) */
        .appendix {
            position: absolute;
            top: 0.25in;
            right: 0.25in;
            font-size: 12px;
            font-style: italic;
        }

        /* Title */
        .header-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 0.5in 0 0.3in;
        }

        /* Two-column header info */
        .header-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0.3in;
        }
        .header-info td {
            vertical-align: top;
            padding: 2px 8px;
        }
        .header-info .label {
            font-weight: bold;
            font-size: 11px;
            margin-right: 4px;
        }
        .header-info .value {
            font-size: 11px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 1px;
        }

        /* Instructions */
        .instructions {
            font-style: italic;
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.3in;
        }

        /* Main table */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0.3in;
        }
        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 6px 4px;
            font-size: 10px;
            text-align: center;
            vertical-align: middle;
        }
        .main-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-left { text-align: left!important; padding-left:8px; }
        .text-right { text-align: right!important; padding-right:8px; }

        /* Column widths */
        .col-ris   { width:12%; }
        .col-rcc   { width:15%; }
        .col-stock { width:10%; }
        .col-item  { width:28%; }
        .col-unit  { width:8%;  }
        .col-qty   { width:10%; }
        .col-cost  { width:10%; }
        .col-amount{ width:12%; }

        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        /* Recapitulation */
        .recapitulation {
            margin-bottom: 0.3in;
        }
        .recap-table {
            width: 100%;
            border-collapse: collapse;
        }
        .recap-table td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 10px;
            text-align: center;
        }
        .recap-title {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 11px;
            text-align: left;
            padding-left: 8px;
        }
        .recap-header {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 11px;
        }

        /* Signatures */
        .signatures {
            margin-top: 0.5in;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            border: 1px solid #000;
            padding: 15px;
            vertical-align: top;
        }
        .cert-text {
            font-size: 10px;
            margin-bottom: 0.4in;
            text-align: left;
        }

        /* Centered, text‐width underline */
        .signature-left {
            text-align: center;
        }
        .signature-content {
            margin-top: 20px;
        }
        .sig-name {
            display: inline-block;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
        }
        .sig-title {
            font-size: 10px;
            text-align: center;
            margin-top: 5px;
        }

        .posted-by {
            font-size: 10px;
            margin-bottom: 0.2in;
            text-align: left;
        }
        .simple-signatures {
            display: flex;
            gap: 30px;
            align-items: flex-end;
        }
        .signature-line, .date-line {
            flex: 1;
            text-align: center;
        }
        .underline {
            border-bottom: 1px solid #000;
            height: 25px;
            margin-bottom: 5px;
        }
        .simple-label {
            font-size: 9px;
            line-height: 1.2;
        }
    </style>
</head>
<body>
    <div class="appendix">Appendix 64</div>

    <div class="header-title">REPORT OF SUPPLIES AND MATERIALS ISSUED</div>

    <table class="header-info">
        <tr>
            <td style="width:60%;">
                <span class="label">Entity Name:</span>
                <span class="value">{{ strtoupper($entityName) }}</span><br>
                <span class="label">Fund Cluster:</span>
                <span class="value">{{ $fundCluster }}</span>
            </td>
            <td style="width:40%;">
                <span class="label">Serial No.:</span>
                <span class="value">&nbsp;</span><br>
                <span class="label">Date:</span>
                <span class="value">{{ $startDate->format('F Y') }}</span>
            </td>
        </tr>
    </table>

    <div class="instructions">
        <span>To be filled up by the Supply and/or Property Division/Unit</span>
        <span>To be filled up by the Accounting Division/Unit</span>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th class="col-ris">RIS No.</th>
                <th class="col-rcc">Responsibility<br>Center Code</th>
                <th class="col-stock">Stock No.</th>
                <th class="col-item">Item</th>
                <th class="col-unit">Unit</th>
                <th class="col-qty">Quantity<br>Issued</th>
                <th class="col-cost">Unit Cost</th>
                <th class="col-amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAmount = 0;
                $recapData = [];
            @endphp

            @foreach($reportData as $risData)
                @foreach($risData['items'] as $index => $item)
                    @php
                        $totalAmount += $item['total_cost'];
                        $stockNo = $item['stock_no'];
                        if (!isset($recapData[$stockNo])) {
                            $recapData[$stockNo] = [
                                'quantity'   => 0,
                                'unit_cost'  => $item['unit_cost'],
                                'total_cost' => 0,
                            ];
                        }
                        $recapData[$stockNo]['quantity']   += $item['quantity_issued'];
                        $recapData[$stockNo]['total_cost'] += $item['total_cost'];
                    @endphp

                    <tr>
                        <td class="col-ris">{{ $risData['ris_no'] }}</td>
                        <td class="col-rcc">&nbsp;</td> {{-- blank Responsibility Center Code --}}
                        <td class="col-stock">{{ $item['stock_no'] }}</td>
                        <td class="text-left col-item">{{ $item['item_name'] }}</td>
                        <td class="col-unit">{{ $item['unit'] }}</td>
                        <td class="col-qty">{{ number_format($item['quantity_issued']) }}</td>
                        <td class="text-right col-cost">{{ number_format($item['unit_cost'], 2) }}</td>
                        <td class="text-right col-amount">{{ number_format($item['total_cost'], 2) }}</td>
                    </tr>
                @endforeach
            @endforeach

            <tr class="total-row">
                <td colspan="7"></td>
                <td class="text-right">{{ number_format($totalAmount, 2) }}</td>
            </tr>
        </tbody>
    </table>


    <div class="recapitulation">
        <table class="recap-table">
            <tr>
                <td class="recap-title" colspan="4">Recapitulation:</td>
            </tr>
            <tr>
                <td class="recap-header">Stock No.</td>
                <td class="recap-header">Quantity</td>
                <td class="recap-header">Unit Cost</td>
                <td class="recap-header">Total Cost</td>
            </tr>
            @php $recapCount = 0; @endphp
            @foreach(array_slice($recapData, 0, 15, true) as $stockNo => $data)
                <tr>
                    <td class="text-left">{{ $stockNo }}</td>
                    <td>{{ number_format($data['quantity']) }}</td>
                    <td class="text-right">{{ number_format($data['unit_cost'], 2) }}</td>
                    <td class="text-right">{{ number_format($data['total_cost'], 2) }}</td>
                </tr>
                @php $recapCount++; @endphp
            @endforeach
            <tr style="border-top: 2px solid #000;">
                <td colspan="3" class="text-right">Total:</td>
                <td class="text-right">{{ number_format($totalAmount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="signatures">
        <table class="signature-table">
            <tr>
                <td class="signature-left">
                    <div class="cert-text">
                        I hereby certify to the correctness of the above information.
                    </div>
                    <div class="signature-content">
                        <div class="sig-name">ALEA MARIE P. DELOSO</div>
                        <div class="sig-title">Supply and/or Property Custodian</div>
                    </div>
                </td>
                <td>
                    <div class="posted-by">Posted by:</div>
                    <div class="simple-signatures">
                        <div class="signature-line">
                            <div class="underline">&nbsp;</div>
                            <div class="simple-label">
                                Signature over Printed Name of<br>
                                Designated Accounting Staff
                            </div>
                        </div>
                        <div class="date-line">
                            <div class="underline">&nbsp;</div>
                            <div class="simple-label">Date</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
