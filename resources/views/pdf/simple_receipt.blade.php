<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin: 20px 0; }
        .amount { text-align: center; font-size: 24px; font-weight: bold; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" style="max-height: 50px; margin-bottom: 10px;">
        @endif
        <h2 style="color: #4f46e5;">Payment Receipt</h2>
        <p>Receipt No: {{ $receipt_number }}</p>
    </div>
    
    <div class="details">
        <p><strong>Student:</strong> {{ $student->name ?? 'N/A' }}</p>
        <p><strong>Hostel:</strong> {{ $hostel->name ?? 'N/A' }}</p>
        <p><strong>Amount:</strong> Rs. {{ number_format($payment->amount, 2) }}</p>
        <p><strong>Date:</strong> {{ $payment->payment_date->format('Y-m-d') }}</p>
        <p><strong>Method:</strong> {{ $payment->payment_method }}</p>
        <p><strong>Description:</strong> {{ $description }}</p>
    </div>
    
    <div class="amount" style="color: #10b981;">
        Total Paid: Rs. {{ number_format($payment->amount, 2) }}
    </div>
    
    <div class="footer">
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>Thank you for your payment!</p>
    </div>
</body>
</html>