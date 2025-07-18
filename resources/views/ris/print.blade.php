<!-- resources/views/ris/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RIS #{{ $risSlip->ris_no }}</title>
    <style>
    @page {
        margin: 0;
        }
        @media print {
        html, body {
            margin: 0;
            padding: 40px;
        }

        /* give yourself a half-inch gutter on whatever paper you choose */
        .container {
            padding: 0.5in;
        }
    }


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
            position: relative;
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
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }
        .signature-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
            width: 25%;
        }
        .signature-label {
            font-weight: bold;
            padding-bottom: 5px;
        }
        .signature-image {
            max-height: 40px;
            margin-bottom: 5px;
            mix-blend-mode: multiply;
            filter: contrast(1.2);
            opacity: 0.9;
        }
        .print-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .appendix {
            position: absolute;
            top: 0.5in;
            right: 0.5in;
            font-style: italic;
            font-size: 15px;
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
            .appendix {
                top: 0;
                right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print">
            <button onclick="window.print()">Print RIS</button>
            <a href="{{ route('ris.show', $risSlip) }}">Back to Details</a>
        </div>

        <div class="appendix">Appendix 63</div>

        <div class="header">
            <h1>REQUISITION AND ISSUE SLIP</h1>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="border: 1px solid #000; padding: 5px; width: 50%;">
                    <strong>Entity Name :</strong> {{ $risSlip->entity_name }}
                </td>
                <td style="border: 1px solid #000; padding: 5px; width: 50%;">
                    <strong>Fund Cluster :</strong> {{ $risSlip->fund_cluster }}
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">
                    <strong>Division :</strong> {{ $risSlip->department->name ?? 'N/A' }}
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <strong>Responsibility Center Code :</strong> {{ $risSlip->responsibility_center_code }}
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">
                    <strong>Office :</strong> {{ $risSlip->office }}
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <strong>RIS No. :</strong> {{ $risSlip->ris_no }}
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <th colspan="4" class="section-header">Requisition</th>
                <th colspan="2" class="section-header">Stock Available?</th>
                <th colspan="2" class="section-header">Issue</th>
            </tr>
            <tr>
                <th>Stock No.</th>
                <th>Unit</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Yes</th>
                <th>No</th>
                <th>Quantity</th>
                <th>Remarks</th>
            </tr>

            @foreach($risSlip->items as $item)
            <tr>
                <td>{{ $item->supply->stock_no ?? 'N/A' }}</td>
                <td>{{ $item->supply->unit_of_measurement ?? 'N/A' }}</td>
                <td>
                    @php
                        $parts = [
                        $item->supply->item_name,
                        $item->supply->description,
                        ];
                        $display = collect($parts)
                                    ->filter()
                                    ->join(', ');
                    @endphp

                    {{ $display ?: 'N/A' }}
                </td>
                <td>{{ $item->quantity_requested }}</td>
                <td style="text-align: center">{{ $item->stock_available ? '✓' : '' }}</td>
                <td style="text-align: center">{{ !$item->stock_available ? '✓' : '' }}</td>
                <td>{{ $item->quantity_issued ?? '' }}</td>
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
                <td>&nbsp;</td>
            </tr>
            @endfor
        </table>

        <div class="purpose-section">
            <div class="purpose-label">Purpose:</div>
            <div class="purpose-content">{{ $risSlip->purpose }}</div>
        </div>

        <table class="signature-table">
            <tr>
                <td>Requested by:</td>
                <td>Approved by:</td>
                <td>Issued by:</td>
                <td>Received by:</td>
            </tr>
            <tr>
                <td>
                    <div style="height: 60px; position: relative;">
                        @if($risSlip->requester && $risSlip->requester_signature_type == 'esign' && $risSlip->requester->signature_path)
                            <img src="{{ Storage::url($risSlip->requester->signature_path) }}"
                                alt="Requester signature"
                                class="signature-image"
                                style="position: absolute; bottom: 0;">
                        @elseif($risSlip->requester)
                            <div style="position: absolute; bottom: 0; font-style: italic; font-weight: bold;">SGD</div>
                        @endif
                    </div>
                </td>
                <td>
                    <div style="height: 60px; position: relative;">
                        @if($risSlip->approved_by && $risSlip->approver_signature_type == 'esign' && $risSlip->approver && $risSlip->approver->signature_path)
                            <img src="{{ Storage::url($risSlip->approver->signature_path) }}"
                                alt="Approver signature"
                                class="signature-image"
                                style="position: absolute; bottom: 0;">
                        @elseif($risSlip->approved_by)
                            <div style="position: absolute; bottom: 0; font-style: italic; font-weight: bold;">SGD</div>
                        @endif
                    </div>
                </td>
                <td>
                    <div style="height: 60px; position: relative;">
                        @if($risSlip->issued_by && $risSlip->issuer_signature_type == 'esign' && $risSlip->issuer && $risSlip->issuer->signature_path)
                            <img src="{{ Storage::url($risSlip->issuer->signature_path) }}"
                                alt="Issuer signature"
                                class="signature-image"
                                style="position: absolute; bottom: 0;">
                        @elseif($risSlip->issued_by)
                            <div style="position: absolute; bottom: 0; font-style: italic; font-weight: bold;">SGD</div>
                        @endif
                    </div>
                </td>
                <td>
                    <div style="height: 60px; position: relative;">
                        @if($risSlip->received_at && $risSlip->receiver_signature_type == 'esign' && $risSlip->receiver && $risSlip->receiver->signature_path)
                            <img src="{{ Storage::url($risSlip->receiver->signature_path) }}"
                                alt="Receiver signature"
                                class="signature-image"
                                style="position: absolute; bottom: 0;">
                        @elseif($risSlip->received_at)
                            <div style="position: absolute; bottom: 0; font-style: italic; font-weight: bold;">SGD</div>
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td>{{ $risSlip->requester->name ?? '____________________' }}</td>
                <td>{{ $risSlip->approved_by ? optional($risSlip->approver)->name : '____________________' }}</td>
                <td>{{ $risSlip->issued_by ? optional($risSlip->issuer)->name : '____________________' }}</td>
                <td>{{ $risSlip->received_by ? optional($risSlip->receiver)->name : '____________________' }}</td>
            </tr>
            <tr>
                <td>{{ optional($risSlip->requester)->designation->name ?? 'Designation' }}</td>
                <td>{{ $risSlip->approved_by ? optional($risSlip->approver)->designation->name ?? 'Designation' : 'Designation' }}</td>
                <td>{{ $risSlip->issued_by ? optional($risSlip->issuer)->designation->name ?? 'Designation' : 'Designation' }}</td>
                <td>{{ $risSlip->received_by ? optional($risSlip->receiver)->designation->name ?? 'Designation' : 'Designation' }}</td>
            </tr>
            <tr>
                <td>{{ $risSlip->created_at->format('M d, Y') }}</td>
                <td>{{ $risSlip->approved_at ? $risSlip->approved_at->format('M d, Y') : '____________________' }}</td>
                <td>{{ $risSlip->issued_at ? $risSlip->issued_at->format('M d, Y') : '____________________' }}</td>
                <td>{{ $risSlip->received_at ? $risSlip->received_at->format('M d, Y') : '____________________' }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
