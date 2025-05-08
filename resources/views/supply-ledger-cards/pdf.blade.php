<!-- supply-ledger-cards/pdf.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supply Ledger Card - {{ $supply->item_name }}</title>
    <style>
        @page {
            margin: 0.5in;
            size: 8.5in 11in;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.3;
        }
        .page {
            position: relative;
            width: 100%;
            height: 100%;
        }
        .appendix {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 15px;
            font-style: italic;
        }
        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 40px 0 20px 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }
        .ledger-table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
            font-size: 10px;
        }
        .ledger-table th, .ledger-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }
        .ledger-table th {
            background-color: #ffffff;
            font-weight: normal;
        }
        .ledger-header {
            height: 30px;
        }
        .text-left {
            text-align: left;
        }
        .no-wrap {
            white-space: nowrap;
        }
        .entity-name {
            font-weight: normal;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="appendix">Appendix 57</div>

        <div class="title">SUPPLIES LEDGER CARD</div>

        <table class="info-table">
            <tr>
                <td colspan="3" class="text-left entity-name">
                    <strong>Entity Name:</strong>
                    COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII
                </td>
                <td colspan="2" class="text-left">
                    <strong>Fund Cluster:</strong> {{ $fundCluster }}
                </td>
            </tr>


            <tr>
                <td class="text-left no-wrap" width="15%">Item:</td>
                <td colspan="2" class="text-left">{{ $supply->item_name }}</td>
                <td class="text-left no-wrap" width="15%">Item Code:</td>
                <td class="text-left" width="20%">{{ $supply->stock_no }}</td>
            </tr>
            <tr>
                <td class="text-left no-wrap">Description:</td>
                <td colspan="2" class="text-left">{{ $supply->description }}</td>
                <td class="text-left no-wrap">Re-order Point:</td>
                <td class="text-left">{{ $supply->reorder_point }}</td>
            </tr>
            <tr>
                <td class="text-left no-wrap">Unit of Measurement:</td>
                <td colspan="2" class="text-left">{{ $supply->unit_of_measurement }}</td>
                <td colspan="2"></td>
            </tr>
        </table>

        <table class="ledger-table">
            <thead>
                <tr class="ledger-header">
                    <th rowspan="2" width="10%">Date</th>
                    <th rowspan="2" width="15%">Reference</th>
                    <th colspan="3" width="22%">Receipt</th>
                    <th colspan="3" width="22%">Issue</th>
                    <th colspan="3" width="22%">Balance</th>
                    <th rowspan="2" width="9%">No. of Days to Consume</th>
                </tr>
                <tr>
                    <th>Qty.</th>
                    <th>Unit Cost</th>
                    <th>Total Cost</th>
                    <th>Qty.</th>
                    <th>Unit Cost</th>
                    <th>Total Cost</th>
                    <th>Qty.</th>
                    <th>Unit Cost</th>
                    <th>Total Cost</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ledgerCardEntries as $entry)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($entry['date'])->format('m/d/Y') }}</td>
                        <td class="text-left">{{ $entry['reference'] }}</td>
                        <td>{{ $entry['receipt_qty'] ? number_format($entry['receipt_qty']) : '' }}</td>
                        <td>{{ $entry['receipt_unit_cost'] ? number_format($entry['receipt_unit_cost'], 2) : '' }}</td>
                        <td>{{ $entry['receipt_total_cost'] ? number_format($entry['receipt_total_cost'], 2) : '' }}</td>
                        <td>{{ $entry['issue_qty'] ? number_format($entry['issue_qty']) : '' }}</td>
                        <td>{{ $entry['issue_unit_cost'] ? number_format($entry['issue_unit_cost'], 2) : '' }}</td>
                        <td>{{ $entry['issue_total_cost'] ? number_format($entry['issue_total_cost'], 2) : '' }}</td>
                        <td>{{ number_format($entry['balance_qty']) }}</td>
                        <td>{{ number_format($entry['balance_unit_cost'], 2) }}</td>
                        <td>{{ number_format($entry['balance_total_cost'], 2) }}</td>
                        <td>{{ $entry['days_to_consume'] ?: '' }}</td>
                    </tr>
                @endforeach

                <!-- Add empty rows to match format -->
                @for ($i = 0; $i < max(15 - count($ledgerCardEntries), 0); $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</body>
</html>
