<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <title>रसिद - {{ $hostel->name ?? 'HostelHub' }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; background: #f8fafc; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #4f46e5; padding-bottom: 25px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 12px; margin: -30px -30px 30px -30px; }
        .logo { max-height: 70px; margin-bottom: 15px; border-radius: 8px; background: white; padding: 8px; }
        .receipt-title { color: white; margin: 10px 0; font-size: 32px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        .details { margin: 30px 0; background: #f8fafc; padding: 25px; border-radius: 12px; border-left: 5px solid #4f46e5; }
        .detail-row { display: flex; margin: 12px 0; padding: 10px 0; border-bottom: 1px dashed #cbd5e0; align-items: center; }
        .detail-label { font-weight: bold; width: 200px; color: #4a5568; font-size: 16px; }
        .detail-value { flex: 1; color: #2d3748; font-size: 16px; }
        .amount-section { text-align: center; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 30px; margin: 35px 0; border-radius: 15px; box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3); border: 2px solid #10b981; }
        .amount { font-size: 42px; font-weight: bold; margin: 15px 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
        .nepali-amount { font-size: 20px; margin-top: 10px; opacity: 0.9; }
        .footer { text-align: center; margin-top: 50px; font-size: 14px; color: #718096; border-top: 2px dashed #e2e8f0; padding-top: 25px; background: #f7fafc; padding: 20px; border-radius: 10px; }
        .nepali { font-family: 'Preeti', 'Lohit Devanagari', 'Mangal', sans-serif; font-weight: bold; }
        .english { font-family: Arial, sans-serif; }
        .watermark { position: absolute; opacity: 0.03; font-size: 120px; transform: rotate(-45deg); top: 40%; left: 10%; color: #4f46e5; font-weight: bold; pointer-events: none; }
        .security-features { display: flex; justify-content: space-around; margin: 20px 0; padding: 15px; background: #edf2f7; border-radius: 10px; font-size: 12px; }
        .receipt-number { background: #4f46e5; color: white; padding: 8px 15px; border-radius: 20px; display: inline-block; margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="watermark">PAID</div>
        
        <div class="header">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" class="logo" alt="Logo">
            @endif
            <h1 class="receipt-title nepali">रसिद</h1>
            <h3 class="english">PAYMENT RECEIPT</h3>
            <div class="receipt-number">रसिद नं: {{ $receipt_number }}</div>
        </div>

        <div class="security-features">
            <div class="nepali">🔒 आधिकारिक रसिद</div>
            <div class="english">✅ Officially Verified</div>
            <div class="nepali">💰 भुक्तानी प्रमाणित</div>
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
                <span class="detail-label nepali">भुक्तानी मिति:</span>
                <span class="detail-value nepali">{{ $payment->payment_date->format('Y-m-d') }} ({{ $payment->payment_date->format('l') }})</span>
            </div>
            <div class="detail-row">
                <span class="detail-label nepali">भुक्तानी विधि:</span>
                <span class="detail-value nepali">
                    @if($payment->payment_method == 'cash') नगद @endif
                    @if($payment->payment_method == 'bank_transfer') बैंक हस्तान्तरण @endif
                    @if($payment->payment_method == 'esewa') eSewa @endif
                    @if($payment->payment_method == 'khalti') खल्ती @endif
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label nepali">भुक्तानी विवरण:</span>
                <span class="detail-value nepali">{{ $description ?? 'भुक्तानी विवरण उपलब्ध छैन' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label nepali">लेनदेन आईडी:</span>
                <span class="detail-value english">TXN-{{ $payment->id }}-{{ strtoupper(uniqid()) }}</span>
            </div>
        </div>

        <div class="amount-section">
            <div class="nepali">कुल भुक्तानी रकम</div>
            <div class="amount english">Rs. {{ number_format($payment->amount, 2) }}</div>
            <div class="nepali nepali-amount">{{ $nepaliAmount ?? 'रुपैयाँ ' . number_format($payment->amount, 2) . ' मात्र' }}</div>
        </div>

        <div class="footer">
            <p class="nepali">📝 यो रसिद आधिकारिक रूपमा मान्य हो र भुक्तानीको प्रमाणको रूपमा काम गर्दछ।</p>
            <p class="english">✅ This receipt is officially valid and serves as proof of payment.</p>
            <p class="english">🖨️ Generated on: {{ $generated_date }}</p>
            <p class="nepali" style="margin-top: 15px; font-size: 12px; opacity: 0.7;">धन्यवाद! होस्टलहब मा आफ्नो भुक्तानी गर्नुभएकोमा 🙏</p>
        </div>
    </div>
</body>
</html>