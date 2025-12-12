<!DOCTYPE html>
<html>
<head>
    <title>Test Bill</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { max-width: 150px; max-height: 100px; border: 1px solid #ccc; }
        .debug { background: #f0f0f0; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="debug">
        <strong>DEBUG INFO:</strong><br>
        Logo Base64: {{ isset($logo_base64) ? 'YES (' . strlen($logo_base64) . ' chars)' : 'NO' }}<br>
        Hostel: {{ $hostel->name ?? 'N/A' }}<br>
        Payment ID: {{ $payment->id ?? 'N/A' }}
    </div>
    
    <div class="header">
        @if(isset($logo_base64) && !empty($logo_base64))
            <div>
                <img src="{{ $logo_base64 }}" class="logo">
            </div>
        @endif
        
        <h1>{{ $hostel->name ?? 'Hostel' }}</h1>
        <p>{{ $hostel->address ?? '' }}</p>
    </div>
    
    <h2>Payment Bill</h2>
    <p>Bill Number: {{ $bill_number ?? 'N/A' }}</p>
    <p>Date: {{ now()->format('Y-m-d') }}</p>
    
    <h3>Student Information</h3>
    <p>Name: {{ $student->name ?? 'N/A' }}</p>
    <p>Amount: Rs. {{ number_format($payment->amount ?? 0, 2) }}</p>
    
    <p style="margin-top: 50px; text-align: center;">
        This is a test bill with logo.
    </p>
</body>
</html>