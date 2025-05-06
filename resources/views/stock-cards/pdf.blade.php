<!-- stock-cards/pdf.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Card - {{ $supply->item_name }}</title>
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
        .stock-table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .stock-table th, .stock-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }
        .stock-table th {
            background-color: #ffffff;
            font-weight: normal;
        }
        .stock-header {
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
        <div class="appendix">Appendix 58</div>

        <div class="title">STOCK CARD</div>

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
                <td class="text-left no-wrap" width="15%">Item :</td>
                <td colspan="2" class="text-left">{{ $supply->item_name }}</td>
                <td class="text-left no-wrap" width="15%">Stock No. :</td>
                <td class="text-left" width="20%">{{ $supply->stock_no }}</td>
            </tr>
            <tr>
                <td class="text-left no-wrap">Description :</td>
                <td colspan="2" class="text-left">{{ $supply->description }}</td>
                <td class="text-left no-wrap">Re-order Point :</td>
                <td class="text-left">{{ $supply->reorder_point }}</td>
            </tr>
            <tr>
                <td class="text-left no-wrap">Unit of Measurement:</td>
                <td colspan="2" class="text-left">{{ $supply->unit_of_measurement }}</td>
                <td colspan="2"></td>
            </tr>
        </table>

        <table class="stock-table">
            <thead>
                <tr class="stock-header">
                    <th rowspan="2" width="12%">Date</th>
                    <th rowspan="2" width="20%">Reference</th>
                    <th colspan="1" width="12%">Receipt</th>
                    <th colspan="2" width="24%">Issue</th>
                    <th colspan="1" width="12%">Balance</th>
                    <th rowspan="2" width="20%">No. of Days to Consume</th>
                </tr>
                <tr>
                    <th>Qty.</th>
                    <th width="12%">Qty.</th>
                    <th width="12%">Office</th>
                    <th>Qty.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stockCardEntries as $entry)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($entry['date'])->format('m/d/Y') }}</td>
                        <td class="text-left">{{ $entry['reference'] }}</td>
                        <td>{{ $entry['receipt_qty'] ?: '' }}</td>
                        <td>{{ $entry['issue_qty'] ?: '' }}</td>
                        <td class="text-left">{{ $entry['issue_office'] ?: '' }}</td>
                        <td>{{ $entry['balance_qty'] }}</td>
                        <td>{{ $entry['days_to_consume'] ?: '' }}</td>
                    </tr>
                @endforeach

                <!-- Add empty rows to match format -->
                @for ($i = 0; $i < max(15 - count($stockCardEntries), 0); $i++)
                    <tr>
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
