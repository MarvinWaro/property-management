<!DOCTYPE html>
<html>
<head>
    <title>Property Sticker</title>
    <meta charset="UTF-8">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f9fafb;
        }
        .container {
            text-align: center;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .qr-code {
            width: 200px;
            height: 200px;
            margin-bottom: 16px;
        }
        .item-name {
            font-size: 1.5rem; /* 24px */
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }
        .property-number {
            font-size: 1.125rem; /* 18px */
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .employee-name {
            font-size: 1.25rem; /* 20px */
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }
        .designation {
            font-size: 0.875rem; /* 14px */
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ $qrCodeImage }}" alt="QR Code" class="qr-code">
        <div class="item-name">{{ $itemName }}</div>
        <div class="property-number">{{ $propertyNumber }}</div>
        <div class="employee-name">{{ $employeeName }}</div>
        <div class="designation">{{ $designation }}</div>

        wewewewew
    </div>
</body>
</html>
