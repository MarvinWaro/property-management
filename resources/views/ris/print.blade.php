
<!-- resources/views/ris/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RIS #{{ $risSlip->ris_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 8.5in;
            margin: 0 auto;
            padding: 0.5in;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .form-header {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
            border: 1px solid #000;
        }
        .form-header div {
            padding: 5px;
            border-bottom: 1px solid #000;
        }
        .form-header div:nth-child(2n) {
            border-left: 1px solid #000;
        }
        .form-header div:nth-last-child(-n+2) {
            border-bottom: none;
        }
        .form-header label {
            display: inline-block;
            width: 140px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .section-header {
            text-align: center;
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .purpose-section {
            margin-bottom: 20px;
            border: 1px solid #000;
        }
        .purpose-label {
            font-weight: bold;
            padding: 5px;
            border-bottom: 1px solid #000;
        }
        .purpose-content {
            padding: 10px;
            min-height: 60px;
        }
        .signatures {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 40px;
        }
        .signature-box {
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid #000;
        }
        .signature-box p {
            margin: 0;
        }
        .signature-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .print-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .appendix {
            text-align: right;
            font-style: italic;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                padding: 0;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="print-header">
            <div class="no-print">
                <button onclick="window.print()">Print RIS</button>
                <a href="{{ route('ris.show', $risSlip) }}">Back to Details</a>
            </div>
            <div class="appendix">Appendix 63</div>
        </div>

        <div class="header">
            <h1>REQUISITION AND ISSUE SLIP</h1>
        </div>

        <div class="form-header">
            <div>
                <label>Entity Name :</label>
                <span>{{ $risSlip->entity_name }}</span>
            </div>
            <div>
                <label>Fund Cluster :</label>
                <span>{{ $risSlip->fund_cluster }}</span>
            </div>
            <div>
                <label>Division :</label>
                <span>{{ $risSlip->division->name ?? 'N/A' }}</span>
            </div>
            <div>
                <label>Responsibility Center Code :</label>
                <span>{{ $risSlip->responsibility_center_code }}</span>
            </div>
            <div>
                <label>Office :</label>
                <span>{{ $risSlip->office }}</span>
            </div>
            <div>
                <label>RIS No. :</label>
                <span>{{ $risSlip->ris_no }}</span>
            </div>
        </div>

        <table>
            <tr>
                <th colspan="3" class="section-header">Requisition</th>
                <th colspan="2" class="section-header">Stock Available?</th>
                <th colspan="2" class="section-header">Issue</th>
            </tr>
            <tr>
                <th>Stock No.</th>
                <th>Unit</th>
                <th>Description</th>
                <th>Yes</th>
                <th>No</th>
                <th>Quantity</th>
                <th>Remarks</th>
            </tr>

            @foreach($risSlip->items as $item)
            <tr>
                <td>{{ $item->supply->stock_no ?? 'N/A' }}</td>
                <td>{{ $item->supply->unit_of_measurement ?? 'N/A' }}</td>
                <td>{{ $item->supply->item_name }}</td>
                <td style="text-align: center">{{ $item->stock_available ? '✓' : '' }}</td>
                <td style="text-align: center">{{ !$item->stock_available ? '✓' : '' }}</td>
                <td>{{ $item->quantity_issued ?? $item->quantity_requested }}</td>
                <td>{{ $item->remarks ?? '' }}</td>
            </tr>
            @endforeach

            <!-- Empty rows for manual entry -->
            @for($i = 0; $i < max(10 - count($risSlip->items), 0); $i++)
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
        </table>

        <div class="purpose-section">
            <div class="purpose-label">Purpose:</div>
            <div class="purpose-content">{{ $risSlip->purpose }}</div>
        </div>

        <div class="signatures">
            <div>
                <div class="signature-title">Requested by:</div>
                <div class="signature-box">
                    <p>{{ $risSlip->requester->name ?? '____________________' }}</p>
                    <p>{{ optional($risSlip->requester)->designation->name ?? 'Designation' }}</p>
                    <p>{{ $risSlip->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div>
                <div class="signature-title">Approved by:</div>
                <div class="signature-box">
                    @if($risSlip->approved_by)
                        <p>{{ optional($risSlip->approver)->name }}</p>
                        <p>{{ optional($risSlip->approver)->designation->name ?? 'Designation' }}</p>
                        <p>{{ $risSlip->approved_at->format('M d, Y') }}</p>
                    @else
                        <p>____________________</p>
                        <p>Designation</p>
                        <p>Date</p>
                    @endif
                </div>
            </div>

            <div>
                <div class="signature-title">Issued by:</div>
                <div class="signature-box">
                    @if($risSlip->issued_by)
                        <p>{{ optional($risSlip->issuer)->name }}</p>
                        <p>{{ optional($risSlip->issuer)->designation->name ?? 'Designation' }}</p>
                        <p>{{ $risSlip->issued_at->format('M d, Y') }}</p>
                    @else
                        <p>____________________</p>
                        <p>Designation</p>
                        <p>Date</p>
                    @endif
                </div>
            </div>

            <div>
                <div class="signature-title">Received by:</div>
                <div class="signature-box">
                    @if($risSlip->received_by)
                        <p>{{ optional($risSlip->receiver)->name }}</p>
                        <p>{{ optional($risSlip->receiver)->designation->name ?? 'Designation' }}</p>
                        <p>{{ $risSlip->received_at->format('M d, Y') }}</p>
                    @else
                        <p>____________________</p>
                        <p>Designation</p>
                        <p>Date</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
