<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>रसिद</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin: 20px 0; }
        .amount { text-align: center; font-size: 24px; font-weight: bold; margin: 20px 0; color: #10b981; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #4f46e5;">भुक्तानी रसिद</h2>
        <p><strong>रसिद नम्बर:</strong> REC-{{ $payment->id ?? 'N/A' }}</p>
    </div>
    
    <div class="details">
        <p><strong>विद्यार्थी:</strong> {{ $student->name ?? 'विद्यार्थी छैन' }}</p>
        <p><strong>होस्टल:</strong> {{ $hostel->name ?? 'होस्टल छैन' }}</p>
        <p><strong>रकम:</strong> रु. {{ number_format($payment->amount ?? 0, 2) }}</p>
        <p><strong>मिति:</strong> {{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : 'N/A' }}</p>
        <p><strong>भुक्तानी विधि:</strong> {{ $payment->payment_method ?? 'N/A' }}</p>
    </div>
    
    <div class="amount">
        जम्मा भुक्तानी: रु. {{ number_format($payment->amount ?? 0, 2) }}
    </div>
    
    <div class="footer">
        <p>यो रसिद निम्न मिति मा सिर्जना गरियो: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>भुक्तानीको लागि धन्यवाद!</p>
    </div>
</body>
</html>