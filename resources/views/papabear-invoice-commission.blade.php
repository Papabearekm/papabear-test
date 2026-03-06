<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 30px;
        }

        .header {
            margin-bottom: 20px;
            text-align: left;
        }

        .logo {
            height: 80px;
            display: block;
            margin-bottom: 10px;
        }

        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .info-table, .items-table, .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .items-table th, .items-table td, .totals-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .amount-in-words {
            margin-top: 20px;
            font-style: italic;
        }

        .page-break {
            page-break-after: always;
        }

        @page {
            margin: 0;
            padding: 0;
            size: A4;
        }
    </style>
</head>
<body>
    <!-- Invoice Header -->
    <div class="header">
        <img src="{{ asset('assets/images/black-logo.png') }}" class="logo" alt="Papa Bear Logo">
        @php
            $addressLines = explode(',', $papabearAddress);
        @endphp
        <p><strong>Sold By:</strong> Papa Bear</p>
        <p style="margin: 0;">
            @foreach ($addressLines as $line)
                <span style="display: block; line-height: 1.4;">{{ trim($line) }}</span>
            @endforeach
        </p>
        <p>PAN: {{ $papabearPAN }}</p>
        <p>GSTIN: {{ $papabearGST }}</p>
    </div>

    <div class="invoice-title">TAX INVOICE</div>

    <table class="info-table">
        <tr>
            <td><strong>Billing Address:</strong><br>
                {{ $sellerName }}<br>
                {{ gettype($sellerAddress) === 'string' ? $sellerAddress :  ($sellerAddress->address . ', ' . $sellerAddress->house . ', ' . $sellerAddress->landmark . ', ' . $sellerAddress->pincode) }}
                <br>GST: {{ $sellerGST }}
            </td>
            <td>
                <strong>Invoice Number:</strong> {{ $papabearInvoiceNumber }}<br>
                <strong>Invoice Date:</strong> {{ $invoiceDate }}<br>
            </td>
        </tr>
    </table>

    <p><strong>State Code:</strong> 32</p>
    <p><strong>Place of Supply:</strong> Kerala</p>
    <p><strong>Place of Delivery:</strong> Kerala</p>

    <table class="items-table">
        <thead>
            <tr>
                <th>Sl No.</th>
                <th>Descriptions</th>
                <th>HSN/SAC</th>
                <th>Unit Price</th>
                <th>Qty</th>
                <th>Net Amount</th>
                <th>Tax Rate (%)</th>
                <th>Tax Amount</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @if ($commission)
                <tr>
                    <td>1</td>
                    <td>Platform fees</td>
                    <td>9985</td>
                    <td>₹ {{ number_format($commission, 2) }}</td>
                    <td>1</td>
                    <td>₹ {{ number_format($commission, 2) }}</td>
                    <td>18</td>
                    <td>₹ {{ number_format(($commission * 0.18), 2) }}</td>
                    <td>₹ {{ number_format($commission + ($commission * 0.18), 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="totals-table" style="margin-top:20px;">
        <tr>
            <td><strong>Total</strong></td>
            <td>₹ {{ number_format($commission + ($commission * 0.18), 2) }}</td>
        </tr>
    </table>

    <div class="amount-in-words">
        Amount in Words: <strong>{{ $amountInWords }}</strong>
    </div>

    <div class="signature">
        For Papa Bear<br>
        Authorised Signatory
    </div>
    <br>
    Digitally Signed by Papa Bear on {{ $invoiceDate }}
</body>
</html>
