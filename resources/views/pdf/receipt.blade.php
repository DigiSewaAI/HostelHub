<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payment Receipt</title>
    <style>
        /* Simple and clean styles */
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            margin: 20px;
            line-height: 1.6;
            font-size: 14px;
            color: #333;
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
        }
        
        .details { 
            margin: 20px 0; 
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background-color: #f9fafb;
        }
        
        .details p {
            margin: 10px 0;
        }
        
        .amount { 
            text-align: center; 
            font-size: 22px; 
            font-weight: bold; 
            margin: 20px 0; 
            color: #10b981;
            padding: 15px;
            background-color: #f0fdf4;
            border-radius: 8px;
            border: 2px solid #10b981;
        }
        
        .footer { 
            text-align: center; 
            margin-top: 30px; 
            font-size: 12px; 
            color: #666;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }
        
        /* Table-like layout */
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .label {
            font-weight: bold;
            width: 50%;
        }
        
        .value {
            width: 50%;
            text-align: right;
        }
        
        /* Status badge */
        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            margin-left: 10px;
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        
        /* Print styles */
        @media print {
            body { 
                margin: 0; 
                padding: 15px;
                font-size: 13px;
            }
            .amount { 
                color: #000 !important;
                border: 2px solid #000;
            }
        }
        
        /* Watermark effect */
        .watermark {
            position: absolute;
            opacity: 0.05;
            font-size: 80px;
            transform: rotate(-45deg);
            z-index: -1;
            white-space: nowrap;
            top: 40%;
            left: 10%;
            color: #4f46e5;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">
        PAID
    </div>
    
    <div class="header">
        <h2 style="color: #4f46e5; margin-bottom: 10px;">PAYMENT RECEIPT</h2>
        <p>
            <strong>Receipt No:</strong> 
            REC-{{ $payment->id ?? 'N/A' }}
            <span class="status">PAID</span>
        </p>
    </div>
    
    <div class="details">
        <div class="row">
            <div class="label">Student:</div>
            <div class="value">{{ $student->name ?? 'No Student' }}</div>
        </div>
        <div class="row">
            <div class="label">Hostel:</div>
            <div class="value">{{ $hostel->name ?? 'No Hostel' }}</div>
        </div>
        <div class="row">
            <div class="label">Amount:</div>
            <div class="value">Rs. {{ number_format($payment->amount ?? 0, 2) }}</div>
        </div>
        <div class="row">
            <div class="label">Date:</div>
            <div class="value">
                @if(isset($payment->payment_date) && $payment->payment_date)
                    {{ $payment->payment_date->format('Y-m-d') }}
                @else
                    {{ now()->format('Y-m-d') }}
                @endif
            </div>
        </div>
        <div class="row">
            <div class="label">Payment Method:</div>
            <div class="value">{{ $payment->payment_method ?? 'Not Specified' }}</div>
        </div>
        
        @if(isset($payment->transaction_id) && $payment->transaction_id)
        <div class="row">
            <div class="label">Transaction ID:</div>
            <div class="value">{{ $payment->transaction_id }}</div>
        </div>
        @endif
        
        @if(isset($payment->paid_by) && $payment->paid_by)
        <div class="row">
            <div class="label">Paid By:</div>
            <div class="value">{{ $payment->paid_by }}</div>
        </div>
        @endif
        
        @if(isset($payment->remarks) && $payment->remarks)
        <div class="row">
            <div class="label">Remarks:</div>
            <div class="value">{{ $payment->remarks }}</div>
        </div>
        @endif
    </div>
    
    <div class="amount">
        TOTAL PAYMENT: Rs. {{ number_format($payment->amount ?? 0, 2) }}
    </div>
    
    <div class="footer">
        <p>This receipt was generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p><strong>Thank you for your payment!</strong></p>
        <p style="margin-top: 10px; font-size: 10px; color: #999;">
            This is an official receipt<br>
            Generated by Hostel Management System
        </p>
        
        <!-- Signature area -->
        <div style="margin-top: 25px; padding-top: 15px; border-top: 1px dashed #ccc;">
            <div style="width: 45%; float: left; text-align: center;">
                <p style="border-top: 1px solid #000; width: 200px; margin: 20px auto 0; padding-top: 5px;">
                    Received By
                </p>
            </div>
            <div style="width: 45%; float: right; text-align: center;">
                <p style="border-top: 1px solid #000; width: 200px; margin: 20px auto 0; padding-top: 5px;">
                    Paid By
                </p>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</body>
</html>