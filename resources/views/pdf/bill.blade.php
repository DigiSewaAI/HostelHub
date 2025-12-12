<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Bill</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* Copy exactly the same CSS from the previous bill template */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; color: #333; margin: 20px; }
        
        .header { 
            display: flex; 
            align-items: center; 
            margin-bottom: 20px; 
            padding-bottom: 15px; 
            border-bottom: 2px solid #dc2626;
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
        }
        
        .logo { 
            max-width: 100%; 
            max-height: 100%;
            object-fit: contain;
        }
        
        .hostel-info { flex: 1; }
        .hostel-name { font-size: 22px; font-weight: bold; color: #1e40af; margin-bottom: 5px; }
        .hostel-details { color: #4b5563; font-size: 11px; }
        .hostel-details div { margin-bottom: 2px; }
        
        .document-title { 
            text-align: center; 
            margin: 20px 0; 
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 6px;
        }
        
        .document-title h1 { 
            color: #dc2626; 
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
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #f59e0b;
            border-radius: 10px;
        }
        
        .amount-label {
            font-size: 16px;
            color: #92400e;
            margin-bottom: 10px;
        }
        
        .amount-value {
            font-size: 28px;
            font-weight: bold;
            color: #d97706;
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
        
        .status-pending { background-color: #fef3c7; color: #92400e; border: 1px solid #f59e0b; }
        .status-paid { background-color: #d1fae5; color: #065f46; border: 1px solid #10b981; }
        
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
    <!-- Header with Logo and Hostel Info -->
    <div class="header">
        <div class="logo-container">
            <!-- ✅ LOGO SECTION - FIXED for DOMPDF -->
            @if(isset($logo_path) && !empty($logo_path))
                <img src="{{ $logo_path }}" class="logo" alt="{{ $hostel->name ?? 'Hostel' }} Logo">
            @elseif(isset($logo_base64) && !empty($logo_base64))
            <img src="{{ $logo_url }}" style="max-width: 150px; max-height: 100px;"> 
            <!-- Fallback to base64 -->
                <img src="{{ $logo_base64 }}" class="logo" alt="{{ $hostel->name ?? 'Hostel' }} Logo">
            @else
                <div style="color: #9ca3af; text-align: center; padding: 5px;">
                    <div style="font-size: 10px;">NO LOGO</div>
                    <div style="font-size: 8px;">{{ substr($hostel->name ?? 'Hostel', 0, 10) }}</div>
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
                
                @if(isset($hostel->website) && !empty($hostel->website))
                    <div>Website: {{ $hostel->website }}</div>
                @endif
            </div>
        </div>
        
        <div class="text-right" style="min-width: 150px;">
            <div style="font-weight: bold; color: #4b5563;">Document No.</div>
            <div style="font-size: 14px; color: #dc2626;">BILL-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="mt-10">
                <div style="font-size: 11px;">Date: {{ now()->format('Y-m-d') }}</div>
                <div style="font-size: 11px;">Page: 1 of 1</div>
            </div>
        </div>
    </div>
    
    <!-- Document Title -->
    <div class="document-title">
        <h1>PAYMENT BILL</h1>
        <div style="color: #6b7280; font-size: 14px;">Hostel Fee Payment Bill</div>
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
                <div class="detail-value">{{ $student->phone ?? ($student->email ?? 'N/A') }}</div>
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
                <div class="detail-label">Bill Amount:</div>
                <div class="detail-value">Rs. {{ number_format($payment->amount ?? 0, 2) }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Bill Date:</div>
                <div class="detail-value">
                    @if(isset($payment->payment_date) && !empty($payment->payment_date))
                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}
                    @else
                        {{ now()->format('Y-m-d') }}
                    @endif
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Due Date:</div>
                <div class="detail-value">
                    @if(isset($payment->due_date) && !empty($payment->due_date))
                        {{ \Carbon\Carbon::parse($payment->due_date)->format('Y-m-d') }}
                        <span class="status-badge status-pending">DUE</span>
                    @else
                        @if(isset($payment->payment_date) && !empty($payment->payment_date))
                            {{ \Carbon\Carbon::parse($payment->payment_date)->addDays(7)->format('Y-m-d') }}
                        @else
                            {{ now()->addDays(7)->format('Y-m-d') }}
                        @endif
                    @endif
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Payment Status:</div>
                <div class="detail-value">
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
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Bill Type:</div>
                <div class="detail-value">Monthly Hostel Fee</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Reference No:</div>
                <div class="detail-value">BL{{ date('Ymd') }}{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>
    </div>
    
    <!-- Amount Section -->
    <div class="amount-section">
        <div class="amount-label">TOTAL AMOUNT DUE</div>
        <div class="amount-value">Rs. {{ number_format($payment->amount ?? 0, 2) }}</div>
        <div style="font-size: 12px; color: #92400e; margin-top: 5px;">
            @if(isset($payment->due_date) && !empty($payment->due_date))
                Due Date: {{ \Carbon\Carbon::parse($payment->due_date)->format('F d, Y') }}
            @else
                @if(isset($payment->payment_date) && !empty($payment->payment_date))
                    Due Date: {{ \Carbon\Carbon::parse($payment->payment_date)->addDays(7)->format('F d, Y') }}
                @else
                    Due Date: {{ now()->addDays(7)->format('F d, Y') }}
                @endif
            @endif
        </div>
    </div>
    
    <!-- Payment Instructions -->
    <div style="margin: 20px 0; padding: 15px; background-color: #f0f9ff; border-radius: 8px; border: 1px solid #7dd3fc;">
        <h4 style="color: #0369a1; margin-bottom: 10px;">Payment Instructions:</h4>
        <div style="font-size: 11px; color: #0c4a6e;">
            1. Please make payment before the due date to avoid late fees.<br>
            2. You can pay via bank transfer, cash, or digital payment methods.<br>
            3. Bank Details: ABC Bank, Account No: 1234567890, Account Name: {{ $hostel->name ?? 'Hostel' }}<br>
            4. For digital payments, use PhonePay/ESewa at 98XXXXXXXX<br>
            5. Keep this bill for your records and present during payment.
        </div>
    </div>
    
    <!-- Terms and Conditions -->
    <div style="margin: 15px 0; font-size: 10px; color: #6b7280; text-align: justify; line-height: 1.3;">
        <strong>Terms & Conditions:</strong> 
        Late payments will incur a 2% monthly interest charge. This bill is generated electronically and is valid without signature. 
        Any discrepancies must be reported within 7 days. The hostel reserves the right to take appropriate action for non-payment.
    </div>
    
    <!-- Signature Area -->
    <div class="signature-area">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div style="margin-top: 5px; font-size: 11px;">Student Signature</div>
        </div>
        
        <div class="signature-box">
            <div class="signature-line"></div>
            <div style="margin-top: 5px; font-size: 11px;">Hostel Manager Signature</div>
            <div style="font-size: 10px; color: #6b7280;">({{ $hostel->name ?? 'Hostel' }})</div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <div>This is a computer-generated document. No signature is required.</div>
        <div style="margin-top: 5px;">
            Generated on: {{ now()->format('Y-m-d H:i:s') }} | 
            System: HostelHub | 
            Contact: {{ $hostel->email ?? 'hostel@example.com' }}
        </div>
        <div style="margin-top: 3px; font-size: 9px; color: #9ca3af;">
            Document ID: BILL-{{ $payment->id }}-{{ now()->format('YmdHis') }}
        </div>
    </div>
</body>
</html>