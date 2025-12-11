<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>बिल</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin: 20px 0; }
        .amount { text-align: center; font-size: 24px; font-weight: bold; margin: 20px 0; color: #f59e0b; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #dc2626;">भुक्तानी बिल</h2>
        <p><strong>बिल नम्बर:</strong> BILL-{{ $payment->id ?? 'N/A' }}</p>
    </div>
    
    <div class="details">
        <p><strong>विद्यार्थी:</strong> {{ $student->name ?? 'विद्यार्थी छैन' }}</p>
        <p><strong>होस्टल:</strong> {{ $hostel->name ?? 'होस्टल छैन' }}</p>
        <p><strong>तिर्न बाँकी रकम:</strong> रु. {{ number_format($payment->amount ?? 0, 2) }}</p>
        <p><strong>बिल मिति:</strong> {{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : 'N/A' }}</p>
        <p><strong>तिर्ने अन्तिम मिति:</strong> 
            @if(isset($payment->due_date) && $payment->due_date)
                {{ $payment->due_date->format('Y-m-d') }}
            @else
                N/A
            @endif
        </p>
    </div>
    
    <div class="amount">
        जम्मा तिर्न बाँकी: रु. {{ number_format($payment->amount ?? 0, 2) }}
    </div>
    
    <div class="footer">
        <p>यो बिल निम्न मिति मा सिर्जना गरियो: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>कृपया अन्तिम मिति भन्दा अगाडि भुक्तान गर्नुहोला</p>
    </div>
</body>
</html>