<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Received</title>
    <style>
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; background-color: #f8fafc; color: #334155; }
        .wrapper { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.05); }
        .header { background-color: #15803d; padding: 40px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 900; letter-spacing: -0.025em; text-transform: uppercase; }
        .content { padding: 40px; }
        .greeting { font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 20px; }
        .details { background-color: #f1f5f9; padding: 24px; border-radius: 16px; margin-bottom: 30px; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
        .detail-label { color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .detail-value { color: #0f172a; font-weight: 800; }
        .footer { padding: 40px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #f1f5f9; }
        .btn { display: inline-block; background-color: #15803d; color: #ffffff; padding: 16px 32px; border-radius: 12px; text-decoration: none; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 20px; }
        .success-icon { display: block; width: 48px; height: 48px; margin: 0 auto 20px; color: #15803d; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Payment Confirmed</h1>
        </div>
        <div class="content">
            <svg class="success-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="greeting">Thank you {{ $payment->client->name }},</p>
            <p>We have successfully received your payment. Your financial record has been updated immediately. Below is the confirmation summary.</p>
            
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Payment Ref</span>
                    <span class="detail-value">#{{ $payment->invoice->document_number ?? 'PMT-'.$payment->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount Paid</span>
                    <span class="detail-value" style="color: #15803d;">{{ number_format($payment->paid_amount) }} {{ $payment->currency }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Method</span>
                    <span class="detail-value">{{ $payment->payment_method }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Remaining Balance</span>
                    <span class="detail-value">{{ number_format($payment->remaining_amount) }} {{ $payment->currency }}</span>
                </div>
            </div>

            <p>If you need an official receipt, you can download it from your portal or reply to this email.</p>
            
            <p style="text-align: center;">
                <a href="{{ config('app.url') }}/sales/payments" class="btn">View Payment History</a>
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Stunning Travel & Tours Ltd. All rights reserved.</p>
            <p>123 Travel Avenue, Kigali, Rwanda</p>
        </div>
    </div>
</body>
</html>
