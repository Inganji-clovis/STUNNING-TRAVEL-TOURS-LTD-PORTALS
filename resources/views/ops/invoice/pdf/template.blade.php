<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->document_number }}</title>
    <style>
        @page {
            margin: 40px 0px 40px 0px; /* Top, Right, Bottom, Left */
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Essential for DOMPDF fixed elements */
        main {
            padding: 20px 40px 20px 40px;
        }

        /* Ribbons */
        .ribbon {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin: 0;
            padding: 0;
            line-height: 0;
            font-size: 0;
        }
        .ribbon td {
            height: 40px;
            padding: 0;
            margin: 0;
        }
        .top-ribbon {
            position: fixed;
            top: -40px;
            left: 0;
            right: 0;
            height: 40px;
        }
        .bottom-ribbon {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            height: 40px;
        }
        .ribbon-black {
            width: 50%;
            background-color: #0f172a;
        }
        .ribbon-red {
            width: 50%;
            background-color: #b91c1c;
        }

        /* Header Elements */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .header-logo {
            text-align: right;
            width: 40%;
        }
        .header-logo img {
            max-width: 250px;
        }
        .header-slogan {
            text-align: center;
            font-style: italic;
            font-size: 10pt;
            vertical-align: bottom;
            padding-bottom: 10px;
        }
        
        .date h4 {
            text-align: right;
            margin: 0;
            font-weight: bold;
            text-decoration: underline;
        }

        .invoice-title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            text-decoration: underline;
            margin-top: 20px;
            margin-bottom: 30px;
            letter-spacing: 1px;
        }

        /* Client Box */
        .client-box {
            border: 1px solid #000;
            width: 50%;
            padding: 10px;
            margin-bottom: 30px;
            font-family: monospace;
            font-size: 10pt;
            min-height: 60px;
        }
        .client-box p {
            margin: 2px 0;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #000;
            padding: 8px 12px;
            vertical-align: top;
        }
        .items-table th {
            font-weight: bold;
            text-align: left;
            font-size: 10pt;
        }
        
        .item-row td {
            border-bottom: none;
            border-top: none;
            padding-top: 15px;
            padding-bottom: 15px;
        }
        
        .passenger-name {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11pt;
        }
        .service-desc {
            font-weight: bold;
            font-size: 11pt;
            margin-top: 5px;
        }

        .price-cell {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
        }

        .total-row td {
            border-top: 1px solid #000;
            font-weight: bold;
        }
        
        .total-label {
            text-align: center;
            font-size: 11pt;
        }

        /* Footer Info */
        .amount-in-words {
            font-weight: bold;
            font-size: 10pt;
            font-family: monospace;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .rate {
            font-weight: bold;
            font-family: monospace;
            margin-bottom: 20px;
        }

        .bank-info {
            font-family: monospace;
            font-size: 10pt;
            font-weight: bold;
            position: relative;
        }
        
        .bank-info p {
            margin: 3px 0;
        }

        /* Stamp */
        .stamp-container {
            position: absolute;
            bottom: 50px;
            right: 50px;
            width: 200px;
            opacity: 0.8;
            text-align: center;
        }
        .stamp-container img {
            width: 100%;
        }
        
        /* Page break fix */
        .page-break {
            page-break-after: always;
        }
        
        .address-footer {
            margin-top: 50px;
            text-align: center;
            font-size: 9pt;
        }
    </style>
</head>
<body>
    <!-- Top Two-Tone Ribbon (Fixed Header) -->
    <table class="ribbon top-ribbon">
        <tr>
            <td class="ribbon-black"></td>
            <td class="ribbon-red"></td>
        </tr>
    </table>

    <!-- Bottom Two-Tone Ribbon (Fixed Footer) -->
    <table class="ribbon bottom-ribbon">
        <tr>
            <td class="ribbon-black"></td>
            <td class="ribbon-red"></td>
        </tr>
    </table>
    
    <main>
        <table class="header-table">
            <tr>
                <td class="header-slogan" style="width: 50%; text-align: left;">
                    Making travel easy and fun!
                </td>
                <td class="header-logo" style="width: 50%; text-align: right;">
                    @php
                        $logoPath = public_path('assets/brand/new-logo.png');
                        $logoData = '';
                        if(file_exists($logoPath)) {
                            // DOMPDF supports image data URIs even without GD, as long as it's just rendering the HTML block
                            // (GD is only strictly required for specific image manipulations or certain CSS alpha backgrounds)
                            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                            $data = file_get_contents($logoPath);
                            $logoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        }
                    @endphp
                    @if($logoData)
                        <img src="{{ $logoData }}" alt="Stunning Travel Logo" style="max-height: 200px; max-width: 400px; object-fit: contain;">
                    @else
                        <h2 style="color:#b91c1c; margin:0; text-transform: uppercase;">Stunning Travel & Tours LTD</h2>
                    @endif
                </td>
            </tr>
        </table>

    <div class="date">
        <h4>Kigali, {{ $invoice->created_at->format('j F Y') }}</h4>
    </div>

    <div class="invoice-title">
        INVOICE {{ str_replace('INV-', '', explode('-', $invoice->document_number)[0] ?? '') }} {{ explode('-', $invoice->document_number)[2] ?? trim(str_replace('INV-', '', $invoice->document_number)) }}
    </div>

    <div class="client-box">
        <div style="margin-bottom: 5px;"><strong>CLIENT FULL NAMES:</strong> {{ strtoupper($invoice->client->name) }}</div>
        @if($invoice->client->tin)
            <div><strong>TIN:</strong> {{ $invoice->client->tin }}</div>
        @elseif($invoice->organization && $invoice->organization->tin)
            <div><strong>TIN:</strong> {{ $invoice->organization->tin }}</div>
        @else
            <div><strong>TIN:</strong> N/A</div>
        @endif
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">PASSENGER, AIRLINE & DESTINATION</th>
                <th style="width: 25%; text-align: center;">TOTAL PRICE RWF</th>
                <th style="width: 25%; text-align: center;">TOTAL PRICE USD</th>
            </tr>
        </thead>
        <tbody>
            @if($invoice->items && $invoice->items->count() > 0)
                @foreach($invoice->items as $item)
                    <tr class="item-row">
                        <td>
                            @if($item->passenger_name)
                                <div class="passenger-name">{{ $item->passenger_name }}</div>
                            @endif
                            
                            @if($item->airline)
                                <div style="font-size: 10pt; color: #444; margin-top: 2px;">Airline: {{ $item->airline }}</div>
                            @endif

                            <div class="service-desc">{{ $item->destination }}</div>
                        </td>
                        <td class="price-cell">
                            {{ $item->price_rwf ? number_format($item->price_rwf, 0) : '' }}
                        </td>
                        <td class="price-cell">
                            {{ $item->price_usd ? number_format($item->price_usd, 0) : '' }}
                        </td>
                    </tr>
                @endforeach
            @else
                <!-- Fallback if no specific items recorded (legacy support) -->
                <tr class="item-row">
                    <td>
                        <div class="service-desc">Services Rendered</div>
                    </td>
                    <td class="price-cell">
                        {{ $invoice->currency === 'RWF' ? number_format($invoice->amount, 0) : '' }}
                    </td>
                    <td class="price-cell">
                        {{ $invoice->currency === 'USD' ? number_format($invoice->amount, 0) : '' }}
                    </td>
                </tr>
            @endif
            
            <tr class="total-row">
                <td class="total-label">TOTAL</td>
                <td class="price-cell">
                    @php
                        $totalRwf = $invoice->items ? $invoice->items->sum('price_rwf') : ($invoice->currency === 'RWF' ? $invoice->amount : 0);
                    @endphp
                    {{ $totalRwf > 0 ? number_format($totalRwf, 0) : '' }}
                </td>
                <td class="price-cell">
                    @php
                        $totalUsd = $invoice->items ? $invoice->items->sum('price_usd') : ($invoice->currency === 'USD' ? $invoice->amount : 0);
                    @endphp
                    {{ $totalUsd > 0 ? number_format($totalUsd, 0) : '' }}
                </td>
            </tr>
        </tbody>
    </table>

    @if($invoice->amount_in_words)
    <div class="amount-in-words">
        AMOUNT IN LETTERS: {{ $invoice->amount_in_words }}
    </div>
    @endif

    @if($invoice->rate)
    <div class="rate">
        RATE: {{ number_format($invoice->rate, 0) }}/1USD
    </div>
    @endif

    <div class="bank-info">
        <p>BANK ACOUNT INFORMATION</p>
        <p>STUNNING TRAVEL & TOURS LTD</p>
        <p>BANK OF KIGALI 100070785317 (FRW)</p>
        <p>BANK OF KIGALI 100070785581 (USD)</p>
        <p>SWIFT/BIC CODE: BK IGRWRW</p>
        <p>IBAN: 100070785581840</p>
        <p>Bank address: Kigali-Rwanda-Chic building</p>
        
        <!-- Placeholder for actual stamp image if available, using the logo as fallback or just text -->
        <div class="stamp-container">
            <!-- You can point this to public_path('assets/brand/stamp.png') if such asset exists -->
        </div>
    </div>
    
    <div class="address-footer">
        <strong>STUNNING TRAVEL & TOURS LTD</strong><br>
        In CHIC building Basement3(-3), Door AE01 Opposite National statistics,<br>
        Next to Inyange Distributors Mob: +250 788 312 724, Office: +250 788 842 000<br>
        Email: stunningtravelrw@gmail.com
    </div>
    </main>
</body>
</html>
