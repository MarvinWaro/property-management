<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report of Supplies and Materials Issued</title>
    <style>
        @page {
            size: 13in 8.5in;
            margin: 0.3in;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1;
        }

        /* Header */
        .header-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .appendix {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 10px;
            font-style: italic;
        }

        /* Entity Info Section */
        .entity-section {
            margin-bottom: 10px;
        }

        .entity-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .entity-left {
            display: flex;
            align-items: center;
        }

        .entity-right {
            display: flex;
            align-items: center;
        }

        .label {
            font-size: 10px;
            margin-right: 5px;
        }

        .value {
            font-size: 10px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            min-width: 300px;
            padding: 0 5px;
        }

        .serial-date-value {
            font-size: 10px;
            border-bottom: 1px solid #000;
            min-width: 150px;
            padding: 0 5px;
            text-align: center;
        }

        /* Instructions */
        .instructions {
            text-align: center;
            font-size: 9px;
            font-style: italic;
            margin: 10px 0;
        }

        /* Main Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 3px;
            font-size: 9px;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        td {
            text-align: center;
            height: 18px;
            vertical-align: middle;
        }

        .text-left {
            text-align: left !important;
            padding-left: 5px;
        }

        .text-right {
            text-align: right !important;
            padding-right: 5px;
        }

        /* Column widths */
        .col-ris { width: 10%; }
        .col-rcc { width: 12%; }
        .col-stock { width: 8%; }
        .col-item { width: 30%; }
        .col-unit { width: 8%; }
        .col-qty { width: 8%; }
        .col-cost { width: 12%; }
        .col-amount { width: 12%; }

        /* Specific columns for accounting section */
        .accounting-headers {
            font-size: 8px;
        }

        .col-pur { width: 3%; font-size: 8px; }
        .col-sch { width: 3%; font-size: 8px; }
        .col-cor { width: 3%; font-size: 8px; }
        .col-ind { width: 3%; font-size: 8px; }
        .col-24 { width: 2%; font-size: 8px; }
        .col-45 { width: 2%; font-size: 8px; }

        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        /* Recapitulation */
        .recapitulation {
            margin-top: 20px;
            font-size: 10px;
        }

        .recap-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .recap-content {
            display: flex;
            gap: 100px;
        }

        .recap-line {
            display: flex;
            margin-bottom: 5px;
        }

        .recap-label {
            width: 80px;
        }

        .recap-value {
            border-bottom: 1px solid #000;
            width: 200px;
            text-align: center;
        }

        /* Signatures */
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-around;
        }

        .signature-box {
            text-align: center;
            width: 350px;
        }

        .sig-label {
            font-size: 10px;
            margin-bottom: 30px;
        }

        .sig-line {
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
        }

        .sig-title {
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="appendix">Appendix 64</div>

    <div class="header-title">REPORT OF SUPPLIES AND MATERIALS ISSUED</div>

    <div class="entity-section">
        <div class="entity-row">
            <div class="entity-left">
                <span class="label">Entity Name:</span>
                <span class="value">{{ strtoupper($entityName) }}</span>
            </div>
            <div class="entity-right">
                <span class="label">Serial No. :</span>
                <span class="serial-date-value">&nbsp;</span>
            </div>
        </div>
        <div class="entity-row">
            <div class="entity-left">
                <span class="label">Fund Cluster:</span>
                <span class="value" style="min-width: 100px;">{{ $fundCluster }}</span>
            </div>
            <div class="entity-right">
                <span class="label">Date :</span>
                <span class="serial-date-value">{{ $startDate->format('F Y') }}</span>
            </div>
        </div>
    </div>

    <div class="instructions">
        <span style="margin-right: 150px;">To be filled up by the Supply and/or Property Division/Unit</span>
        <span>To be filled up by the Accounting Division/Unit</span>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" class="col-ris">RIS No.</th>
                <th rowspan="2" class="col-rcc">Responsibility<br>Center Code</th>
                <th rowspan="2" class="col-stock">Stock No.</th>
                <th rowspan="2" class="col-item">Item</th>
                <th rowspan="2" class="col-unit">Unit</th>
                <th rowspan="2" class="col-qty">Quantity<br>Issued</th>
                <th rowspan="2" class="col-cost">Unit<br>Cost</th>
                <th rowspan="2" class="col-amount">Amount</th>
                <th colspan="6" class="accounting-headers">To be filled up by the Accounting Division/Unit</th>
            </tr>
            <tr>
                <th class="col-pur">PUR<br>CHASE<br>ORD<br>ER</th>
                <th class="col-sch">Scholarship/<br>Division</th>
                <th class="col-cor">CORRECTION<br>TAPE</th>
                <th class="col-ind">INDICES</th>
                <th class="col-24">24.40</th>
                <th class="col-45">45.60</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAmount = 0;
                $rowCount = 0;
            @endphp

            @foreach($reportData as $risData)
                @foreach($risData['items'] as $index => $item)
                    @php
                        $totalAmount += $item['total_cost'];
                        $rowCount++;
                    @endphp
                    <tr>
                        <td>{{ $risData['ris_no'] }}</td>
                        <td>{{ $risData['department'] }}</td>
                        <td>{{ $item['stock_no'] }}</td>
                        <td class="text-left">{{ strtoupper($item['item_name']) }}</td>
                        <td>{{ strtoupper($item['unit']) }}</td>
                        <td>{{ number_format($item['quantity_issued']) }}</td>
                        <td class="text-right">{{ number_format($item['unit_cost'], 2) }}</td>
                        <td class="text-right">{{ number_format($item['total_cost'], 2) }}</td>
                        <td class="col-pur"></td>
                        <td class="col-sch"></td>
                        <td class="col-cor"></td>
                        <td class="col-ind"></td>
                        <td class="col-24"></td>
                        <td class="col-45"></td>
                    </tr>
                @endforeach
            @endforeach

            @for($i = $rowCount; $i < 20; $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="col-pur">&nbsp;</td>
                    <td class="col-sch">&nbsp;</td>
                    <td class="col-cor">&nbsp;</td>
                    <td class="col-ind">&nbsp;</td>
                    <td class="col-24">&nbsp;</td>
                    <td class="col-45">&nbsp;</td>
                </tr>
            @endfor

            <tr class="total-row">
                <td colspan="7" class="text-right" style="padding-right: 10px;">TOTAL</td>
                <td class="text-right">{{ number_format($totalAmount, 2) }}</td>
                <td class="col-pur"></td>
                <td class="col-sch"></td>
                <td class="col-cor"></td>
                <td class="col-ind"></td>
                <td class="col-24"></td>
                <td class="col-45"></td>
            </tr>
        </tbody>
    </table>

    <div class="recapitulation">
        <div class="recap-title">RECAPITULATION</div>
        <div class="recap-content">
            <div>
                <div class="recap-line">
                    <span class="recap-label">Stock No.</span>
                    <span class="recap-value">&nbsp;</span>
                </div>
                <div class="recap-line">
                    <span class="recap-value" style="margin-left: 80px;">&nbsp;</span>
                </div>
                <div class="recap-line">
                    <span class="recap-value" style="margin-left: 80px;">&nbsp;</span>
                </div>
                <div class="recap-line">
                    <span class="recap-value" style="margin-left: 80px;">&nbsp;</span>
                </div>
                <div class="recap-line">
                    <span class="recap-value" style="margin-left: 80px;">&nbsp;</span>
                </div>
            </div>
            <div>
                <div class="recap-line">
                    <span class="recap-label">Total Amount</span>
                    <span class="recap-value">&nbsp;</span>
                </div>
                <div class="recap-line">
                    <span class="recap-value" style="margin-left: 80px;">&nbsp;</span>
                </div>
                <div class="recap-line">
                    <span class="recap-value" style="margin-left: 80px;">&nbsp;</span>
                </div>
                <div class="recap-line">
                    <span class="recap-value" style="margin-left: 80px;">&nbsp;</span>
                </div>
                <div class="recap-line">
                    <span class="recap-label">Grand Total:</span>
                    <span class="recap-value"><strong>{{ number_format($totalAmount, 2) }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="signatures">
        <div class="signature-box">
            <div class="sig-label">Prepared by:</div>
            <div class="sig-line">&nbsp;</div>
            <div class="sig-title">Supply and/or Property Custodian</div>
        </div>
        <div class="signature-box">
            <div class="sig-label">Certified Correct:</div>
            <div class="sig-line">&nbsp;</div>
            <div class="sig-title">Head, Accounting Unit/Authorized Representative</div>
        </div>
    </div>
</body>
</html>
