<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Generated</title>
    <style>
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; background-color: #f8fafc; color: #334155; }
        .wrapper { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.05); }
        .header { background-color: #991b1b; padding: 40px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 900; letter-spacing: -0.025em; text-transform: uppercase; }
        .content { padding: 40px; }
        .greeting { font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 20px; }
        .details { background-color: #f1f5f9; padding: 24px; border-radius: 16px; margin-bottom: 30px; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
        .detail-label { color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .detail-value { color: #0f172a; font-weight: 800; }
        .footer { padding: 40px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #f1f5f9; }
        .btn { display: inline-block; background-color: #991b1b; color: #ffffff; padding: 16px 32px; border-radius: 12px; text-decoration: none; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Stunning Travel & Tours</h1>
        </div>
        <div class="content">
            <p class="greeting">Hello {{ $invoice->client->name }},</p>
            <p>A new invoice has been generated for your recent travel arrangements. Please find the details below and the attached PDF for your records.</p>
            
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Invoice Number</span>
                    <span class="detail-value">#{{ $invoice->document_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Issue Date</span>
                    <span class="detail-value">{{ $invoice->created_at->format('M d, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Amount</span>
                    <span class="detail-value">{{ number_format($invoice->amount) }} {{ $invoice->currency }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" style="color: #b91c1c;">{{ strtoupper($invoice->status) }}</span>
                </div>
            </div>

            <p>If you have any questions regarding this invoice, please don't hesitate to reach out to our financial department.</p>
            
            <p style="text-align: center;">
                <a href="{{ config('app.url') }}/sales/invoices/{{ $invoice->id }}/pdf" class="btn">View Online Invoice</a>
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Stunning Travel & Tours Ltd. All rights reserved.</p>
            <p>123 Travel Avenue, Kigali, Rwanda</p>
        </div>
    </div>
</body>
</html>
