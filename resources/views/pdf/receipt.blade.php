<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <style>
        /* Receipt-specific styling - Green theme */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; color: #333; margin: 20px; }
        
        .header { 
            display: flex; 
            align-items: center; 
            margin-bottom: 20px; 
            padding-bottom: 15px; 
            border-bottom: 2px solid #10b981; /* Green for receipt */
        }
        
        .logo-container { 
            width: 80px; 
            height: 80px; 
            margin-right: 20px; 
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f9fafb;
        }
        
        .logo { 
            max-width: 100%; 
            max-height: 100%;
            object-fit: contain;
        }
        
        .no-logo-placeholder {
            color: #9ca3af;
            text-align: center;
            padding: 5px;
            font-size: 10px;
        }
        
        .hostel-info { flex: 1; }
        .hostel-name { font-size: 22px; font-weight: bold; color: #1e40af; margin-bottom: 5px; }
        .hostel-details { color: #4b5563; font-size: 11px; }
        .hostel-details div { margin-bottom: 2px; }
        
        .document-title { 
            text-align: center; 
            margin: 20px 0; 
            padding: 10px;
            background-color: #f0fdf4; /* Light green */
            border-radius: 6px;
            border: 1px solid #bbf7d0;
        }
        
        .document-title h1 { 
            color: #059669; /* Green for receipt */
            font-size: 24px; 
            margin-bottom: 5px; 
        }
        
        .columns {
            display: flex;
            gap: 30px;
            margin: 20px 0;
        }
        
        .column {
            flex: 1;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background-color: #f9fafb;
        }
        
        .column h3 {
            color: #374151;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 8px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #e5e7eb;
        }
        
        .detail-label {
            font-weight: bold;
            color: #4b5563;
            min-width: 120px;
        }
        
        .detail-value {
            color: #111827;
            text-align: right;
            flex: 1;
        }
        
        .amount-section {
            text-align: center;
            margin: 25px 0;
            padding: 20px;
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); /* Green gradient */
            border: 2px solid #10b981;
            border-radius: 10px;
        }
        
        .amount-label {
            font-size: 16px;
            color: #065f46;
            margin-bottom: 10px;
        }
        
        .amount-value {
            font-size: 28px;
            font-weight: bold;
            color: #059669;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        
        .signature-area {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px dashed #d1d5db;
        }
        
        .signature-box {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 40px auto 5px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .status-paid { 
            background-color: #d1fae5; 
            color: #065f46; 
            border: 1px solid #10b981; 
        }
        
        .watermark {
            position: absolute;
            opacity: 0.03;
            font-size: 120px;
            transform: rotate(-45deg);
            z-index: -1;
            white-space: nowrap;
            top: 40%;
            left: 10%;
            color: #10b981;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }
        
        .text-right { text-align: right; }
        .mt-10 { margin-top: 10px; }
        .mb-5 { margin-bottom: 5px; }
        
        @media print {
            body { margin: 10px; }
            .no-print { display: none; }
            .amount-value { color: #000 !important; }
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">PAID</div>
    
    <!-- Header with Logo and Hostel Info -->
    <div class="header">
        <!-- ✅ LOGO SECTION - FIXED for DOMPDF -->
        <div class="logo-container">
            @if(isset($logo_path) && !empty($logo_path))
                <img src="{{ $logo_path }}" class="logo" alt="{{ $hostel->name ?? 'Hostel' }} Logo">
            @elseif(isset($logo_base64) && !empty($logo_base64) && str_starts_with($logo_base64, 'data:image'))
           <img src="{{ $logo_url }}" style="max-width: 150px; max-height: 100px;">
            <!-- Fallback to base64 -->
                <img src="{{ $logo_base64 }}" class="logo" alt="{{ $hostel->name ?? 'Hostel' }} Logo">
            @else
                <div class="no-logo-placeholder">
                    <div style="font-size: 10px; margin-bottom: 3px;">NO LOGO</div>
                    <div style="font-size: 8px;">{{ $hostel->name ?? 'Hostel' }}</div>
                </div>
            @endif
        </div>
        
        <div class="hostel-info">
            <div class="hostel-name">{{ $hostel->name ?? 'Hostel Name' }}</div>
            <div class="hostel-details">
                @if(isset($hostel->address) && $hostel->address)
                    <div>{{ $hostel->address }}</div>
                @endif
                
                @if(isset($hostel->phone) && $hostel->phone)
                    <div>Phone: {{ $hostel->phone }}</div>
                @endif
                
                @if(isset($hostel->email) && $hostel->email)
                    <div>Email: {{ $hostel->email }}</div>
                @endif
                
                @if(isset($hostel->website) && $hostel->website)
                    <div>Website: {{ $hostel->website }}</div>
                @endif
            </div>
        </div>
        
        <div class="text-right" style="min-width: 150px;">
            <div style="font-weight: bold; color: #4b5563;">Receipt No.</div>
            <div style="font-size: 14px; color: #059669;">REC-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="mt-10">
                <div style="font-size: 11px;">Date: {{ now()->format('Y-m-d') }}</div>
                <div style="font-size: 11px;">Page: 1 of 1</div>
            </div>
        </div>
    </div>
    
    <!-- Document Title -->
    <div class="document-title">
        <h1>OFFICIAL PAYMENT RECEIPT</h1>
        <div style="color: #6b7280; font-size: 14px;">Payment Received Successfully</div>
    </div>
    
    <!-- Two Column Layout -->
    <div class="columns">
        <!-- Student Details -->
        <div class="column">
            <h3>Student Details</h3>
            
            <div class="detail-row">
                <div class="detail-label">Full Name:</div>
                <div class="detail-value">{{ $student->name ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Student ID:</div>
                <div class="detail-value">{{ $student->student_id ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Room No:</div>
                <div class="detail-value">
                    @if(isset($student->room) && $student->room)
                        {{ $student->room->room_number ?? 'N/A' }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Contact:</div>
                <div class="detail-value">{{ $student->phone ?? $student->email ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Guardian:</div>
                <div class="detail-value">{{ $student->guardian_name ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Guardian Contact:</div>
                <div class="detail-value">{{ $student->guardian_phone ?? 'N/A' }}</div>
            </div>
        </div>
        
        <!-- Payment Details -->
        <div class="column">
            <h3>Payment Details</h3>
            
            <div class="detail-row">
                <div class="detail-label">Paid Amount:</div>
                <div class="detail-value">Rs. {{ number_format($payment->amount ?? 0, 2) }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Payment Date:</div>
                <div class="detail-value">
                    @if(isset($payment->payment_date) && $payment->payment_date)
                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}
                    @else
                        {{ now()->format('Y-m-d') }}
                    @endif
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Payment Method:</div>
                <div class="detail-value">{{ $payment->payment_method ?? 'N/A' }}</div>
            </div>
            
            @if(isset($payment->transaction_id) && $payment->transaction_id)
            <div class="detail-row">
                <div class="detail-label">Transaction ID:</div>
                <div class="detail-value">{{ $payment->transaction_id }}</div>
            </div>
            @endif
            
            <div class="detail-row">
                <div class="detail-label">Payment Status:</div>
                <div class="detail-value">
                    <span class="status-badge status-paid">PAID</span>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Received By:</div>
                <div class="detail-value">{{ $payment->verifiedBy->name ?? 'Hostel Office' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Receipt Type:</div>
                <div class="detail-value">Monthly Hostel Fee</div>
            </div>
        </div>
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
    <div style="margin: 20px 0; padding: 15px; background-color: #f0f9ff; border-radius: 8px; border: 1px solid #7dd3fc;">
        <h4 style="color: #0369a1; margin-bottom: 10px;">Payment Notes:</h4>
        <div style="font-size: 11px; color: #0c4a6e;">
            1. This receipt is an official acknowledgment of payment received.<br>
            2. Please keep this receipt for your records and future reference.<br>
            3. For any queries, contact the hostel office during working hours.<br>
            4. Payment received via {{ $payment->payment_method ?? 'N/A' }} on {{ \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y') }}.<br>
            5. This is a computer-generated receipt and is valid without signature.
        </div>
    </div>
    
    <!-- Signature Area -->
    <div class="signature-area">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div style="margin-top: 5px; font-size: 11px;">Paid By (Student/Parent)</div>
        </div>
        
        <div class="signature-box">
            <div class="signature-line"></div>
            <div style="margin-top: 5px; font-size: 11px;">Received By (Hostel Manager)</div>
            <div style="font-size: 10px; color: #6b7280;">({{ $hostel->name ?? 'Hostel' }})</div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <div>This is an official receipt. Valid for all purposes.</div>
        <div style="margin-top: 5px;">
            Generated on: {{ now()->format('Y-m-d H:i:s') }} | 
            System: HostelHub | 
            Contact: {{ $hostel->email ?? 'hostel@example.com' }}
        </div>
        <div style="margin-top: 3px; font-size: 9px; color: #9ca3af;">
            Receipt ID: REC-{{ $payment->id }}-{{ now()->format('YmdHis') }}
        </div>
    </div>
</body>
</html>