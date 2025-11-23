<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>HostelHub - बुकिंग अपडेट</title>
    <style>
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
        }
        .booking-card {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .booking-detail {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        .booking-label {
            font-weight: bold;
            color: #555;
        }
        .booking-value {
            color: #333;
        }
        .status-approved {
            color: #28a745;
            font-weight: bold;
        }
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }
        .action-button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            font-weight: bold;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🏠 HostelHub</h1>
            <p>तपाईंको आवास समाधान</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>नमस्ते {{ $booking->getCustomerName() ?? 'प्रिय ग्राहक' }},</h2>
            
            @if($isGuest)
                <p>तपाईंको होस्टल बुकिंग अनुरोध सफलतापूर्वक प्राप्त भएको छ। हामी तपाईंको अनुरोधलाई शीघ्र प्रक्रिया गर्नेछौं।</p>
                
                <div class="note">
                    <strong>📝 महत्वपूर्ण जानकारी:</strong><br>
                    तपाईंले यो बुकिंगलाई स्थायी रूपमा रूपान्तरण गर्न र पूर्ण विद्यार्थी खाता प्राप्त गर्न <strong>खाता दर्ता गर्नुहोस्</strong>।
                </div>
            @else
                @if($status === 'approved')
                    <p>🎉 तपाईंको बुकिंग अनुरोध स्वीकृत भएको छ! तपाईंलाई निर्धारित मितिमा होस्टलमा आउन अनुरोध छ।</p>
                @elseif($status === 'rejected')
                    <p>😔 तपाईंको बुकिंग अनुरोध अस्वीकृत भएको छ। कुनै प्रश्न भएमा होस्टल प्रबन्धकसँग सम्पर्क गर्नुहोस्।</p>
                @else
                    <p>तपाईंको बुकिंगको स्थितिमा परिवर्तन भएको छ।</p>
                @endif
            @endif

            <!-- Booking Details Card -->
            <div class="booking-card">
                <h3 style="margin-top: 0;">📋 बुकिंग विवरण</h3>
                
                <div class="booking-detail">
                    <span class="booking-label">बुकिंग आईडी:</span>
                    <span class="booking-value">#{{ $booking->id }}</span>
                </div>
                
                <div class="booking-detail">
                    <span class="booking-label">होस्टल:</span>
                    <span class="booking-value">{{ $booking->hostel->name ?? 'निर्धारण हुन बाँकी' }}</span>
                </div>
                
                <div class="booking-detail">
                    <span class="booking-label">चेक-इन मिति:</span>
                    <span class="booking-value">{{ optional($booking->check_in_date)->format('Y-m-d') ?? 'निर्धारण हुन बाँकी' }}</span>
                </div>
                
                @if($booking->room_id)
                <div class="booking-detail">
                    <span class="booking-label">कोठा नं.:</span>
                    <span class="booking-value">{{ $booking->room->room_number ?? 'निर्धारण हुन बाँकी' }}</span>
                </div>
                @endif
                
                <div class="booking-detail">
                    <span class="booking-label">स्थिति:</span>
                    <span class="booking-value status-{{ $booking->status }}">
                        @if($booking->status === 'approved')
                            ✅ स्वीकृत
                        @elseif($booking->status === 'pending')
                            ⏳ पेन्डिङ
                        @elseif($booking->status === 'rejected')
                            ❌ अस्वीकृत
                        @else
                            {{ $booking->status }}
                        @endif
                    </span>
                </div>

                @if($booking->amount > 0)
                <div class="booking-detail">
                    <span class="booking-label">रकम:</span>
                    <span class="booking-value">रु {{ number_format($booking->amount, 2) }}</span>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div style="text-align: center; margin: 30px 0;">
                @if($isGuest)
                    <a href="{{ route('register') }}" class="action-button">
                        📝 खाता दर्ता गर्नुहोस्
                    </a>
                @endif
                
                <a href="{{ url('/my-bookings') }}" class="action-button" style="background: #6c757d;">
                    👀 सबै बुकिंग हेर्नुहोस्
                </a>
            </div>

            <!-- Additional Information -->
            @if($isGuest)
            <div class="note">
                <strong>ℹ️ गेस्ट बुकिंग बारे:</strong><br>
                • तपाईंले खाता दर्ता गरेपछि मात्र यो बुकिंग स्थायी हुनेछ<br>
                • खाता दर्ता गर्दा उही इमेल प्रयोग गर्नुहोस्<br>
                • विद्यार्थी खाता बनाउन स्वीकृत बुकिंग आवश्यक छ
            </div>
            @endif

            <p>कुनै प्रश्न वा सहायता चाहियो भने हामीलाई सम्पर्क गर्नुहोस्।</p>
            
            <p>धन्यवाद सहित,<br>
            <strong>HostelHub टिम</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} HostelHub. सर्वाधिकार सुरक्षित।</p>
            <p>यो इमेल स्वचालित रूपमा पठाइएको हो। कृपया यसलाई जवाफ नदिनुहोस्।</p>
            <p>📞 सम्पर्क: +९७७-९८०XXXXXXX | ✉️ ईमेल: support@hostelhub.com</p>
        </div>
    </div>
</body>
</html>