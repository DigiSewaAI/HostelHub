<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Bill - {{ $hostel->name }}</title>
    <style>
        /* DOMPDF COMPATIBLE CSS ONLY */
        body { 
            font-family: helvetica, sans-serif; 
            font-size: 12px; 
            line-height: 1.4; 
            color: #000; 
            margin: 0; 
            padding: 20px;
        }
        
        .header { 
            width: 100%; 
            margin-bottom: 20px; 
            padding-bottom: 15px; 
            border-bottom: 2px solid #dc2626;
        }
        
        .logo-container { 
            width: 80px; 
            height: 80px; 
            float: left; 
            margin-right: 20px; 
            text-align: center;
            border: 1px solid #ddd;
        }
        
        .logo { 
            max-width: 80px; 
            max-height: 80px; 
        }
        
        .hostel-info { 
            overflow: hidden;
        }
        
        .hostel-name { 
            font-size: 18px; 
            font-weight: bold; 
            color: #1e40af; 
            margin: 0 0 5px 0;
        }
        
        .hostel-details { 
            color: #666; 
            font-size: 10px; 
            margin: 0;
        }
        
        .document-title { 
            text-align: center; 
            margin: 20px 0; 
            padding: 10px;
            background-color: #f3f4f6;
        }
        
        .document-title h1 { 
            color: #dc2626; 
            font-size: 20px; 
            margin: 0 0 5px 0; 
        }
        
        .columns-container {
            width: 100%;
            margin: 20px 0;
        }
        
        .column-left {
            width: 48%;
            float: left;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9fafb;
        }
        
        .column-right {
            width: 48%;
            float: right;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9fafb;
        }
        
        .clear {
            clear: both;
        }
        
        .column h3 {
            color: #374151;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 5px;
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        
        .detail-row {
            width: 100%;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px dotted #ddd;
        }
        
        .detail-label {
            display: inline-block;
            width: 120px;
            font-weight: bold;
            color: #4b5563;
        }
        
        .detail-value {
            display: inline-block;
            color: #000;
        }
        
        .amount-section {
            text-align: center; 
            margin: 25px 0; 
            padding: 15px;
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
        }
        
        .amount-label {
            font-size: 16px;
            color: #92400e;
            margin-bottom: 10px;
        }
        
        .amount-value {
            font-size: 24px;
            font-weight: bold;
            color: #d97706;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        
        .text-right {
            text-align: right;
            float: right;
        }
        
        .status-badge {
            display: inline;
            padding: 2px 8px;
            font-size: 10px;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .status-pending { 
            background-color: #fef3c7; 
            color: #92400e; 
        }
        .status-paid { 
            background-color: #d1fae5; 
            color: #065f46; 
        }

        /* Payment methods section styles */
        .payment-method-card {
            margin-bottom: 15px; 
            padding: 10px; 
            border: 1px solid #d1d5db; 
            border-radius: 5px; 
            background-color: white;
        }
        
        .payment-method-header {
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 8px;
        }
        
        .payment-type {
            color: #1e40af; 
            font-size: 12px; 
            font-weight: bold;
        }
        
        .default-badge {
            background-color: #10b981; 
            color: white; 
            padding: 2px 8px; 
            border-radius: 12px; 
            font-size: 10px;
        }
        
        .qr-container {
            margin-top: 10px; 
            text-align: center;
        }
        
        .qr-label {
            color: #6b7280; 
            font-size: 10px; 
            margin-bottom: 5px;
        }
        
        .qr-image {
            width: 80px; 
            height: 80px; 
            display: inline-block; 
            border: 1px solid #d1d5db;
        }
        
        .instructions-container {
            margin-top: 10px; 
            padding-top: 8px; 
            border-top: 1px dashed #d1d5db; 
            font-size: 10px; 
            color: #6b7280;
        }
    </style>
</head>
<body>
    <!-- Header with Logo and Hostel Info -->
    <div class="header">
        <!-- Logo Container -->
        <div class="logo-container">
            @if(isset($logo_base64) && !empty($logo_base64))
                <img src="{{ $logo_base64 }}" class="logo" alt="{{ $hostel->name ?? 'Hostel' }} Logo">
            @else
                <div style="width: 80px; height: 80px; background-color: #3b82f6; color: white; text-align: center; line-height: 80px; font-weight: bold; font-size: 20px;">
                    {{ substr($hostel->name ?? 'H', 0, 1) }}
                </div>
            @endif
        </div>
        
        <div class="hostel-info">
            <div class="hostel-name">{{ $hostel->name ?? 'Hostel Name' }}</div>
            <div class="hostel-details">
                @if(isset($hostel->address) && !empty($hostel->address))
                    <div>{{ $hostel->address }}</div>
                @endif
                @if(isset($hostel->phone) && !empty($hostel->phone))
                    <div>Phone: {{ $hostel->phone }}</div>
                @endif
                @if(isset($hostel->email) && !empty($hostel->email))
                    <div>Email: {{ $hostel->email }}</div>
                @endif
            </div>
        </div>
        
        <div class="text-right">
            <div style="font-weight: bold; color: #4b5563;">Document No.</div>
            <div style="font-size: 14px; color: #dc2626;">BILL-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div style="font-size: 10px; margin-top: 5px;">
                <div>Date: {{ now()->format('Y-m-d') }}</div>
                <div>Page: 1 of 1</div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    
    <!-- Document Title -->
    <div class="document-title">
        <h1>PAYMENT BILL</h1>
        <div style="color: #666; font-size: 12px;">Hostel Fee Payment Bill</div>
    </div>
    
    <!-- Two Column Layout -->
    <div class="columns-container">
        <!-- Student Details -->
        <div class="column-left">
            <h3>Student Details</h3>
            
            <div class="detail-row">
                <span class="detail-label">Full Name:</span>
                <span class="detail-value">{{ $student->name ?? 'N/A' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Student ID:</span>
                <span class="detail-value">{{ $student->student_id ?? 'N/A' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Room No:</span>
                <span class="detail-value">
                    @if(isset($student->room) && $student->room)
                        {{ $student->room->room_number ?? 'N/A' }}
                    @else
                        N/A
                    @endif
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Contact:</span>
                <span class="detail-value">{{ $student->phone ?? ($student->email ?? 'N/A') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Guardian:</span>
                <span class="detail-value">{{ $student->guardian_name ?? 'N/A' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Guardian Contact:</span>
                <span class="detail-value">{{ $student->guardian_phone ?? 'N/A' }}</span>
            </div>
        </div>
        
        <!-- Payment Details -->
        <div class="column-right">
            <h3>Payment Details</h3>
            
            <div class="detail-row">
                <span class="detail-label">Bill Amount:</span>
                <span class="detail-value">Rs. {{ number_format($payment->amount ?? 0, 2) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Bill Date:</span>
                <span class="detail-value">
                    @if(isset($payment->payment_date) && !empty($payment->payment_date))
                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}
                    @else
                        {{ now()->format('Y-m-d') }}
                    @endif
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span class="detail-value">{{ $payment->payment_method ?? 'N/A' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Payment Status:</span>
                <span class="detail-value">
                    @if(isset($payment->status))
                        @if($payment->status == 'completed')
                            <span class="status-badge status-paid">PAID</span>
                        @elseif($payment->status == 'pending')
                            <span class="status-badge status-pending">PENDING</span>
                        @else
                            {{ ucfirst($payment->status) }}
                        @endif
                    @else
                        <span class="status-badge status-pending">PENDING</span>
                    @endif
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Bill Type:</span>
                <span class="detail-value">Monthly Hostel Fee</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Reference No:</span>
                <span class="detail-value">BL{{ date('Ymd') }}{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    
    <!-- Amount Section -->
    <div class="amount-section">
        <div class="amount-label">TOTAL AMOUNT DUE</div>
        <div class="amount-value">Rs. {{ number_format($payment->amount ?? 0, 2) }}</div>
        <div style="font-size: 12px; color: #92400e; margin-top: 5px;">
            @if(isset($payment->due_date) && !empty($payment->due_date))
                Due Date: {{ \Carbon\Carbon::parse($payment->due_date)->format('F d, Y') }}
            @else
                Due Date: {{ now()->addDays(7)->format('F d, Y') }}
            @endif
        </div>
    </div>

    @php
        // Get active payment methods for the hostel
        $paymentMethods = $hostel->bill_payment_methods ?? [];
        $hasPaymentMethods = !empty($paymentMethods);
    @endphp

    @if($hasPaymentMethods)
        <!-- Payment Methods Section -->
        <div style="margin: 20px 0; padding: 15px; background-color: #f0f9ff; border: 1px solid #7dd3fc;">
            <h4 style="color: #0369a1; margin: 0 0 15px 0; text-align: center; font-size: 14px;">
                भुक्तानी गर्ने विधिहरू
            </h4>

            @foreach($paymentMethods as $method)
                <div class="payment-method-card">
                    <div class="payment-method-header">
                        <strong class="payment-type">{{ $method['type_text'] }}</strong>
                        @if($method['is_default'])
                            <span class="default-badge">
                                मुख्य विधि
                            </span>
                        @endif
                    </div>
                    
                    <div style="font-size: 11px; color: #374151;">
                        <strong>{{ $method['title'] }}</strong>
                        
                        @foreach($method['display_info']['details'] as $label => $value)
                            @if(!empty($value))
                                <div style="margin-top: 4px;">
                                    <span style="color: #6b7280;">{{ $label }}:</span>
                                    <span style="color: #111827; font-weight: 500;">{{ $value }}</span>
                                </div>
                            @endif
                        @endforeach
                        
                        @if(!empty($method['qr_code_url']))
                            <div class="qr-container">
                                <div class="qr-label">QR कोड स्क्यान गर्नुहोस्:</div>
                                <img src="{{ $method['qr_code_url'] }}" class="qr-image">
                            </div>
                        @endif
                    </div>
                    
                    @if(!empty($method['instructions']))
                        <div class="instructions-container">
                            <strong>निर्देशनहरू:</strong>
                            <ul style="margin: 5px 0 0 0; padding-left: 15px;">
                                @foreach($method['instructions'] as $instruction)
                                    <li>{{ $instruction }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endforeach
            
            <div style="margin-top: 15px; padding: 10px; background-color: #fef3c7; border-radius: 5px; font-size: 10px; color: #92400e;">
                <strong>नोट:</strong> भुक्तानी गरेपछि रसिद होस्टेल कार्यालयमा पेश गर्नुहोस्।
            </div>
        </div>
    @else
        <!-- Fallback if no payment methods are configured -->
        <div style="margin: 20px 0; padding: 15px; background-color: #fee2e2; border: 1px solid #fca5a5;">
            <h4 style="color: #dc2626; margin: 0 0 10px 0; text-align: center; font-size: 14px;">
                भुक्तानी विवरण
            </h4>
            <div style="text-align: center; font-size: 11px; color: #7f1d1d;">
                भुक्तानी गर्नका लागि होस्टेल कार्यालयमा सम्पर्क गर्नुहोस्।
                <br>
                फोन: {{ $hostel->phone ?? 'N/A' }}
                <br>
                इमेल: {{ $hostel->email ?? 'N/A' }}
            </div>
        </div>
    @endif
    
    <!-- Footer -->
    <div class="footer">
        <div>This is a computer-generated document.</div>
        <div style="margin-top: 5px;">
            Generated on: {{ now()->format('Y-m-d H:i:s') }} | 
            System: HostelHub
        </div>
        <div style="margin-top: 3px; font-size: 8px; color: #999;">
            Document ID: BILL-{{ $payment->id }}-{{ now()->format('YmdHis') }}
        </div>
    </div>
</body>
</html>