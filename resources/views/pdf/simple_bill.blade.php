<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bill</title>
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
        <h2 style="color: #dc2626;">Payment Invoice</h2>
        <p>Bill No: {{ $bill_number }}</p>
    </div>
    
    <div class="details">
        <p><strong>Student:</strong> {{ $student->name ?? 'N/A' }}</p>
        <p><strong>Hostel:</strong> {{ $hostel->name ?? 'N/A' }}</p>
        <p><strong>Amount Due:</strong> Rs. {{ number_format($payment->amount, 2) }}</p>
        <p><strong>Bill Date:</strong> {{ $payment->payment_date->format('Y-m-d') }}</p>
        <p><strong>Due Date:</strong> {{ $payment->due_date ? $payment->due_date->format('Y-m-d') : 'N/A' }}</p>
        <p><strong>Description:</strong> {{ $description }}</p>
    </div>
    
    <div class="amount" style="color: #f59e0b;">
        Total Due: Rs. {{ number_format($payment->amount, 2) }}
    </div>
    
    <div class="footer">
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>Please pay before due date</p>
    </div>
</body>
</html>