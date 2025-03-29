<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Property Sticker - Landscape with Logo</title>

    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f9fafb;
        }

        .page-container {
            width: 297mm;
            height: 210mm;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            padding: 10mm;
            box-sizing: border-box;
        }

        .sticker-container {
            width: 95mm;
            height: 140mm;
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 24px;
            box-sizing: border-box;
            text-align: center;
        }

        .company-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .company-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 8px;
        }

        .company-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            margin-bottom: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
        }

        .item-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .property-number {
            font-size: 1rem;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 12px;
            background-color: #f3f4f6;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .employee-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 12px;
        }

        .employee-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .designation {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .date-issued {
            font-size: 0.75rem;
            color: #9ca3af;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="sticker-container">

            <!-- Company Logo & Name -->
            <div class="company-header">
                <img src="{{ asset('img/r12-logo.png') }}" alt="Company Logo" />
            </div>

            <!-- QR Code -->
            <img src="{{ $qrCodeImage }}" alt="QR Code" class="qr-code">

            <!-- Asset Info -->
            <div class="item-name">{{ $itemName }}</div>
            <div class="property-number">Property No: {{ $propertyNumber }}</div>

            <!-- Employee Info -->
            <div class="employee-details">
                <div class="employee-name">{{ $employeeName }}</div>
                <div class="designation">{{ $designation }}</div>
            </div>

        </div>
    </div>
</body>

</html>


