<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <title>बिल - {{ $hostel->name ?? 'HostelHub' }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; background: #fff5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #fed7d7; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #dc2626; padding-bottom: 25px; background: linear-gradient(135deg, #fc8181 0%, #e53e3e 100%); color: white; padding: 25px; border-radius: 12px; margin: -30px -30px 30px -30px; }
        .logo { max-height: 70px; margin-bottom: 15px; border-radius: 8px; background: white; padding: 8px; }
        .bill-title { color: white; margin: 10px 0; font-size: 32px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        .details { margin: 30px 0; background: #fef2f2; padding: 25px; border-radius: 12px; border-left: 5px solid #dc2626; }
        .detail-row { display: flex; margin: 12px 0; padding: 10px 0; border-bottom: 1px dashed #feb2b2; align-items: center; }
        .detail-label { font-weight: bold; width: 200px; color: #742a2a; font-size: 16px; }
        .detail-value { flex: 1; color: #2d3748; font-size: 16px; }
        .amount-section { text-align: center; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 30px; margin: 35px 0; border-radius: 15px; box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3); border: 2px solid #f59e0b; }
        .amount { font-size: 42px; font-weight: bold; margin: 15px 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
        .nepali-amount { font-size: 20px; margin-top: 10px; opacity: 0.9; }
        .footer { text-align: center; margin-top: 50px; font-size: 14px; color: #742a2a; border-top: 2px dashed #fed7d7; padding-top: 25px; background: #fef2f2; padding: 20px; border-radius: 10px; }
        .nepali { font-family: 'Preeti', 'Lohit Devanagari', 'Mangal', sans-serif; font-weight: bold; }
        .english { font-family: Arial, sans-serif; }
        .watermark { position: absolute; opacity: 0.03; font-size: 120px; transform: rotate(-45deg); top: 40%; left: 10%; color: #dc2626; font-weight: bold; pointer-events: none; }
        .due-date-alert { background: #fed7d7; color: #742a2a; padding: 15px; border-radius: 10px; margin: 20px 0; text-align: center; border: 2px dashed #e53e3e; }
        .bill-number { background: #dc2626; color: white; padding: 8px 15px; border-radius: 20px; display: inline-block; margin-top: 10px; font-weight: bold; }
        .payment-instructions { background: #feebc8; padding: 15px; border-radius: 10px; margin: 20px 0; border-left: 4px solid #dd6b20; }
    </style>
</head>
<body>
    <div class="container">
        <div class="watermark">DUE</div>
        
        <div class="header">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" class="logo" alt="Logo">
            @endif
            <h1 class="bill-title nepali">बिल</h1>
            <h3 class="english">PAYMENT INVOICE</h3>
            <div class="bill-number">बिल नं: {{ $bill_number }}</div>
        </div>

        <div class="due-date-alert">
            <div class="nepali">⏰ कृपया यो बिल {{ $payment->due_date ? $payment->due_date->format('Y-m-d') : 'मिति' }} भित्र भुक्तानी गर्नुहोस्</div>
            <div class="english">Please pay this bill before {{ $payment->due_date ? $payment->due_date->format('Y-m-d') : 'due date' }}</div>
        </div>

        <div class="details">
            <div class="detail-row">
                <span class="detail-label nepali">विद्यार्थीको नाम:</span>
                <span class="detail-value nepali">{{ $student->name ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label nepali">होस्टलको नाम:</span>
                <span class="detail-value nepali">{{ $hostel->name ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label nepali">कोठा नं:</span>
                <span class="detail-value nepali">{{ $room->room_number ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label nepali">बिल मिति:</span>
                <span class="detail-value nepali">{{ $payment->payment_date->format('Y-m-d') }} ({{ $payment->payment_date->format('l') }})</span>
            </div>
            <div class="detail-row">
                <span class="detail-label nepali">अन्तिम मिति:</span>
                <span class="detail-value nepali" style="color: #dc2626; font-weight: bold;">
                    {{ $payment->due_date ? $payment->due_date->format('Y-m-d') . ' (' . $payment->due_date->format('l') . ')' : 'N/A' }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label nepali">बिल विवरण:</span>
                <span class="detail-value nepali">{{ $description ?? 'बिल विवरण उपलब्ध छैन' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label nepali">बिल आईडी:</span>
                <span class="detail-value english">INV-{{ $payment->id }}-{{ strtoupper(uniqid()) }}</span>
            </div>
        </div>

        <div class="payment-instructions">
            <div class="nepali">💳 भुक्तानी विधिहरू: नगद, बैंक हस्तान्तरण, eSewa, खल्ती</div>
            <div class="english">Payment Methods: Cash, Bank Transfer, eSewa, Khalti</div>
        </div>

        <div class="amount-section">
            <div class="nepali">कुल बिल रकम</div>
            <div class="amount english">Rs. {{ number_format($payment->amount, 2) }}</div>
            <div class="nepali nepali-amount">{{ $nepaliAmount ?? 'रुपैयाँ ' . number_format($payment->amount, 2) . ' मात्र' }}</div>
        </div>

        <div class="footer">
            <p class="nepali">📝 यो बिल आधिकारिक रूपमा मान्य हो र भुक्तानीको लागि अनिवार्य छ।</p>
            <p class="english">✅ This invoice is officially valid and mandatory for payment.</p>
            <p class="english">🖨️ Generated on: {{ $generated_date }}</p>
            <p class="nepali" style="margin-top: 15px; font-size: 12px; opacity: 0.7;">भुक्तानी नगर्दा सेवामा रोक लाग्न सक्छ। ⚠️</p>
        </div>
    </div>
</body>
</html>