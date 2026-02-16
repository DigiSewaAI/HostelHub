<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payment Bill - {{ $hostel->name ?? 'HostelHub' }}</title>
    <style>
        /* तपाईंको CSS यथावत रहन्छ */
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            margin: 0;
            padding: 15px;
            color: #000;
        }
        
        .header {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 10px;
        }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header-table td {
            vertical-align: top;
            padding: 0;
        }
        
        .logo-cell {
            width: 80px;
            padding-right: 10px;
        }
        
        .logo-container {
            width: 70px;
            height: 70px;
            border: 1px solid #ddd;
            overflow: hidden;
        }
        
        .logo-img {
            width: 100%;
            height: 100%;
        }
        
        .info-cell {
            width: auto;
        }
        
        .hostel-name {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin: 0 0 3px 0;
        }
        
        .hostel-details {
            font-size: 9px;
            color: #666;
            margin: 0;
            line-height: 1.3;
        }
        
        .bill-info {
            text-align: right;
            font-size: 10px;
            width: 180px;
            white-space: nowrap;
        }
        
        .contact-person {
            font-size: 8px;
            color: #4b5563;
            margin-top: 2px;
        }
        
        /* Rest of the CSS remains the same */
        .title {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background: #f3f4f6;
        }
        
        .title h1 {
            color: #dc2626;
            font-size: 18px;
            margin: 0 0 5px 0;
        }
        
        .title h2 {
            color: #666;
            font-size: 12px;
            margin: 0;
        }
        
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .content-table td {
            padding: 8px;
            vertical-align: top;
            border: 1px solid #ddd;
            background: #f9fafb;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #374151;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 5px;
            margin: 0 0 8px 0;
        }
        
        .row {
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1px dotted #ddd;
        }
        
        .label {
            display: inline-block;
            width: 110px;
            font-weight: bold;
            color: #4b5563;
        }
        
        .value {
            display: inline-block;
            color: #000;
        }
        
        .amount-box {
            text-align: center;
            margin: 15px 0;
            padding: 12px;
            background: #fef3c7;
            border: 2px solid #f59e0b;
        }
        
        .amount-label {
            font-size: 14px;
            color: #92400e;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        
        .amount-value {
            font-size: 22px;
            font-weight: bold;
            color: #d97706;
            margin: 0;
        }
        
        .bank-box {
            margin: 15px 0;
            padding: 12px;
            background: #f0f9ff;
            border: 1px solid #7dd3fc;
        }
        
        .bank-title {
            font-size: 12px;
            font-weight: bold;
            color: #0369a1;
            margin: 0 0 8px 0;
            text-align: center;
        }
        
        .bank-row {
            margin-bottom: 5px;
        }
        
        .bank-label {
            display: inline-block;
            width: 100px;
            font-weight: bold;
            color: #1e40af;
        }
        
        .bank-value {
            display: inline-block;
            color: #1e3a8a;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        
        .status {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 10px;
            background: #d1fae5;
            color: #065f46;
        }
        
        .due-alert {
            background: #fed7d7;
            color: #742a2a;
            padding: 8px;
            text-align: center;
            font-size: 10px;
            margin: 10px 0;
            border: 1px dashed #e53e3e;
        }
    </style>
</head>
<body>
    <!-- Header - USING TABLE FOR PERFECT ALIGNMENT -->
    <div class="header">
        <table class="header-table">
            <tr>
                <!-- Logo Column -->
                <td class="logo-cell">
                    <div class="logo-container">
                        @if(isset($logo_base64) && !empty($logo_base64))
                            <img src="{{ $logo_base64 }}" class="logo-img">
                        @else
                            <div style="width:100%;height:100%;background:#3b82f6;color:white;text-align:center;line-height:70px;font-weight:bold;font-size:18px">
                                {{ substr($hostel->name ?? 'H', 0, 1) }}
                            </div>
                        @endif
                    </div>
                </td>
                
                <!-- Hostel Info Column -->
                <td class="info-cell">
                    <div class="hostel-name">{{ $hostel->name ?? 'Hostel Name' }}</div>
                    <div class="hostel-details">
                        {{ $clean_address ?? ($hostel->address ?? 'Address not specified') }}<br>
                        Phone: {{ $contact_phone ?? ($hostel->phone ?? 'N/A') }} | 
                        Email: {{ $contact_email ?? ($hostel->email ?? 'N/A') }}
                        @if(isset($owner_name) && !empty($owner_name))
                            <div class="contact-person">Contact Person: {{ $owner_name }}</div>
                        @endif
                    </div>
                </td>
                
                <!-- Bill Info Column (Right Aligned) -->
                <td class="bill-info">
                    <div><strong>Bill No:</strong> {{ $bill_number }}</div>
                    <div>Date: {{ now()->format('Y-m-d') }}</div>
                    <div>Page: 1 of 1</div>
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Title -->
    <div class="title">
        <h1>PAYMENT BILL</h1>
        <h2>Hostel Fee Payment Bill</h2>
    </div>
    
    <!-- Due Date Alert -->
    @if(isset($payment->due_date))
    <div class="due-alert">
        Please pay this bill before {{ \Carbon\Carbon::parse($payment->due_date)->format('F d, Y') }}
    </div>
    @endif
    
    <!-- Two Columns -->
    <table class="content-table">
        <tr>
            <td width="50%">
                <div class="section-title">Student Details</div>
                
                <div class="row">
                    <span class="label">Full Name:</span>
                    <span class="value">{{ $student->name ?? 'N/A' }}</span>
                </div>
                
                <div class="row">
                    <span class="label">Student ID:</span>
                    <span class="value">{{ $student->id ?? 'N/A' }}</span>
                </div>
                
                <div class="row">
                    <span class="label">Room No:</span>
                    <span class="value">
                        @if(isset($room) && $room)
                            {{ $room->room_number ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                
                <div class="row">
                    <span class="label">Contact:</span>
                    <span class="value">{{ $student->phone ?? 'N/A' }}</span>
                </div>
                
                <div class="row">
                    <span class="label">Guardian:</span>
                    <span class="value">{{ $student->guardian_name ?? 'N/A' }}</span>
                </div>
                
                <!-- FIX 1: Guardian Contact with fallback guardian_contact -->
                <div class="row">
                    <span class="label">Guardian Contact:</span>
                    <span class="value">{{ $student->guardian_phone ?? $student->guardian_contact ?? 'N/A' }}</span>
                </div>
            </td>
            
            <td width="50%">
                <div class="section-title">Payment Details</div>
                
                <div class="row">
                    <span class="label">Bill Amount:</span>
                    <span class="value">Rs. {{ number_format($payment->amount ?? 0, 2) }}</span>
                </div>
                
                <div class="row">
                    <span class="label">Bill Date:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</span>
                </div>
                
                <!-- ✅ परिवर्तन: Due Date (payment->due_date, payment->invoice->due_date, वा payment->payment_date) -->
                <div class="row">
                    <span class="label">Due Date:</span>
                    <span class="value">
                        {{ \Carbon\Carbon::parse($payment->due_date ?? ($payment->invoice->due_date ?? $payment->payment_date))->format('Y-m-d') }}
                    </span>
                </div>
                
                <!-- ✅ नयाँ: Billing Month (यदि payment सँग invoice छ भने) -->
                @if($payment->invoice)
                <div class="row">
                    <span class="label">Billing Month:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($payment->invoice->billing_month)->format('F Y') }}</span>
                </div>
                @endif
                
                <!-- FIX 2: Payment Method mapping -->
                <div class="row">
                    <span class="label">Payment Method:</span>
                    <span class="value">
                        @php
                            $methodMap = [
                                'cash' => 'Cash',
                                'bank_transfer' => 'Bank Transfer',
                                'esewa' => 'eSewa',
                                'khalti' => 'Khalti',
                                'connectips' => 'Connect IPS',
                                'credit_card' => 'Credit Card',
                                'online' => 'Online',
                                'cheque' => 'Cheque',
                                'wallet' => 'Wallet',
                                'other' => 'Other',
                            ];
                            $paymentMethod = $methodMap[$payment->payment_method] ?? ucfirst($payment->payment_method ?? 'N/A');
                        @endphp
                        {{ $paymentMethod }}
                    </span>
                </div>
                
                <div class="row">
                    <span class="label">Payment Status:</span>
                    <span class="value">
                        @if($payment->status == 'completed')
                            <span class="status">PAID</span>
                        @else
                            <span class="status" style="background:#fef3c7;color:#92400e">PENDING</span>
                        @endif
                    </span> <!-- FIX 3: Closing tag added -->
                </div>
                
                <div class="row">
                    <span class="label">Bill Type:</span>
                    <span class="value">Monthly Hostel Fee</span>
                </div>
                
                <div class="row">
                    <span class="label">Reference No:</span>
                    <span class="value">BL{{ date('Ymd') }}{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
            </td>
        </tr>
    </table>
    
    <!-- Amount -->
    <div class="amount-box">
        <div class="amount-label">TOTAL AMOUNT DUE</div>
        <div class="amount-value">Rs. {{ number_format($payment->amount ?? 0, 2) }}</div>
        @if(isset($payment->due_date))
        <div style="font-size:10px;color:#92400e;margin-top:5px">
            Due Date: {{ \Carbon\Carbon::parse($payment->due_date)->format('F d, Y') }}
        </div>
        @endif
    </div>
    
    <!-- Bank Details -->
    <div class="bank-box">
        <div class="bank-title">Bank Details</div>
        
        @if(!empty($bank_details['bank_name']))
            <div class="bank-row">
                <span class="bank-label">Bank Name:</span>
                <span class="bank-value">{{ $bank_details['bank_name'] }}</span>
            </div>
            
            <div class="bank-row">
                <span class="bank-label">Account Name:</span>
                <span class="bank-value">{{ $bank_details['account_name'] ?? $hostel->name }}</span>
            </div>
            
            <div class="bank-row">
                <span class="bank-label">Account Number:</span>
                <span class="bank-value">{{ $bank_details['account_number'] }}</span>
            </div>
            
            @if(!empty($bank_details['branch']))
            <div class="bank-row">
                <span class="bank-label">Branch:</span>
                <span class="bank-value">{{ $bank_details['branch'] }}</span>
            </div>
            @endif
            
            @if(!empty($bank_details['swift_code']))
            <div class="bank-row">
                <span class="bank-label">Swift Code:</span>
                <span class="bank-value">{{ $bank_details['swift_code'] }}</span>
            </div>
            @endif
        @else
            <div style="padding:10px; text-align:center; color:#666; font-size:11px;">
                For bank details, please contact the hostel office.
            </div>
        @endif
        
        <div style="margin-top:10px;padding:8px;background:#fef3c7;font-size:9px;color:#92400e">
            <strong>Note:</strong> Please include Bill No. {{ $bill_number }} in payment reference.
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <div>This is a computer-generated document.</div>
        <div style="margin-top:3px">
            Generated on: {{ $generated_date ?? now()->format('Y-m-d H:i:s') }} | System: HostelHub
        </div>
        <div style="margin-top:3px;font-size:7px;color:#999">
            Document ID: {{ $bill_number }}-{{ now()->format('YmdHis') }}
        </div>
        <div style="margin-top:5px;font-size:8px">
            For any queries, contact: {{ $contact_phone ?? ($hostel->phone ?? 'N/A') }} | {{ $contact_email ?? ($hostel->email ?? 'N/A') }}
        </div>
    </div>
</body>
</html>