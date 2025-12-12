<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt - {{ $hostel->name }}</title>
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
            border-bottom: 2px solid #10b981;
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
            background-color: #f0fdf4;
        }
        
        .document-title h1 { 
            color: #059669; 
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
            background-color: #d1fae5;
            border: 2px solid #10b981;
        }
        
        .amount-label {
            font-size: 16px;
            color: #065f46;
            margin-bottom: 10px;
        }
        
        .amount-value {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
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
        
        .status-paid { 
            background-color: #d1fae5; 
            color: #065f46; 
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
                <div style="width: 80px; height: 80px; background-color: #10b981; color: white; text-align: center; line-height: 80px; font-weight: bold; font-size: 20px;">
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
            <div style="font-weight: bold; color: #4b5563;">Receipt No.</div>
            <div style="font-size: 14px; color: #059669;">REC-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div style="font-size: 10px; margin-top: 5px;">
                <div>Date: {{ now()->format('Y-m-d') }}</div>
                <div>Page: 1 of 1</div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    
    <!-- Document Title -->
    <div class="document-title">
        <h1>PAYMENT RECEIPT</h1>
        <div style="color: #666; font-size: 12px;">Payment Received Successfully</div>
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
                <span class="detail-label">Paid Amount:</span>
                <span class="detail-value">Rs. {{ number_format($payment->amount ?? 0, 2) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Payment Date:</span>
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
                <span class="detail-label">Transaction ID:</span>
                <span class="detail-value">{{ $payment->transaction_id ?? 'N/A' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Payment Status:</span>
                <span class="detail-value">
                    <span class="status-badge status-paid">PAID</span>
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Received By:</span>
                <span class="detail-value">{{ $payment->verifiedBy->name ?? 'Hostel Office' }}</span>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    
    <!-- Amount Section -->
    <div class="amount-section">
        <div class="amount-label">AMOUNT RECEIVED</div>
        <div class="amount-value">Rs. {{ number_format($payment->amount ?? 0, 2) }}</div>
        <div style="font-size: 12px; color: #065f46; margin-top: 5px;">
            Payment received with thanks.
        </div>
    </div>
    
    <!-- Payment Notes -->
    <div style="margin: 20px 0; padding: 10px; background-color: #f0f9ff; border: 1px solid #7dd3fc;">
        <h4 style="color: #0369a1; margin: 0 0 8px 0;">Payment Notes:</h4>
        <div style="font-size: 10px; color: #0c4a6e;">
            1. This is an official receipt.<br>
            2. Please keep for your records.<br>
            3. Thank you for your payment.
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <div>This is an official receipt. Valid for all purposes.</div>
        <div style="margin-top: 5px;">
            Generated on: {{ now()->format('Y-m-d H:i:s') }} | 
            System: HostelHub
        </div>
        <div style="margin-top: 3px; font-size: 8px; color: #999;">
            Receipt ID: REC-{{ $payment->id }}-{{ now()->format('YmdHis') }}
        </div>
    </div>
</body>
</html>