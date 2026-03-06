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
    </style>
</head>
<body>
    <!-- Second Invoice -->
    <div class="header">
        @php
            $isWeb = app()->runningInConsole() ? false : true;
            $logoPath = $isWeb ? asset('assets/images/black-logo.png') : public_path('assets/images/black-logo.png');
            $addressLines = explode(',', $sellerAddress);
        @endphp
        <img src="{{ $logoPath }}" class="logo" alt="Papa Bear Logo">

        <p><strong>Sold By:</strong> {{ $sellerName }}</p>
        <p style="margin: 0;">
            @foreach ($addressLines as $line)
                <span style="display: block; line-height: 1.4;">{{ trim($line) }}</span>
            @endforeach
        </p>
        <p>PAN: {{ $sellerPan }}</p>
        <p>GSTIN: {{ $sellerGST }}</p>
    </div>

    <div class="invoice-title">TAX INVOICE</div>

    <table class="info-table">
        <tr>
            <td><strong>Billing Address:</strong><br>
                {{ $customerName }}<br>
                {{ gettype($customerAddress) === 'string' ? $customerAddress :  ($customerAddress->address . ', ' . $customerAddress->house . ', ' . $customerAddress->landmark . ', ' . $customerAddress->pincode)}}</td>
            @if ($isShipping)
            <td><strong>Site Address:</strong><br>
            {{ $customerName }}<br>
            {{ gettype($customerAddress) === 'string' ? $customerAddress :  ($customerAddress->address . ', ' . $customerAddress->house . ', ' . $customerAddress->landmark . ', ' . $customerAddress->pincode)}}</td>
            @endif
            <td>
                <strong>Invoice Number:</strong> {{ $sellerInvoiceNumber }}<br>
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
            @foreach ($items['services'] as $serviceItem)
                @php
                    $serviceDetails = DB::table('services')->where('id', $serviceItem['service_id'])->first();
                    $totalWithoutTax = $serviceItem['off'];
                @endphp
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $serviceItem['name'] }}</td>
                    <td>{{ $serviceDetails ? $serviceDetails->hsn_code : '-' }}</td>
                    <td>₹ {{ number_format($totalWithoutTax, 2) }}</td>
                    <td>1</td>
                    <td>₹ {{ number_format($totalWithoutTax, 2) }}</td>
                    <td>18</td>
                    <td>₹ {{ number_format(($totalWithoutTax * 0.18), 2) }}</td>
                    <td>₹ {{ number_format($totalWithoutTax + ($totalWithoutTax * 0.18), 2) }}</td>
                </tr>
            @endforeach
            @foreach ($items['packages'] as $packageItem)
                @php
                $totalWithoutTax = $packageItem['off'];
                @endphp
                <tr>
                    <td>{{ sizeof($items['services']) + $loop->index + 1 }}</td>
                    <td>{{ $packageItem['name'] }}</td>
                    <td>-</td>
                    <td>₹ {{ number_format($totalWithoutTax, 2) }}</td>
                    <td>1</td>
                    <td>₹ {{ number_format($totalWithoutTax, 2) }}</td>
                    <td>18</td>
                    <td>₹ {{ number_format(($totalWithoutTax * 0.18), 2) }}</td>
                    <td>₹ {{ number_format($totalWithoutTax + ($totalWithoutTax * 0.18), 2) }}</td>
                </tr>
            @endforeach
            @if ($items['distance_cost'])
                @php
                    $distanceWithTax = $items['distance_cost'];
                    $distanceBasePrice = $distanceWithTax / 1.18;
                    $distanceTaxAmount = $distanceWithTax - $distanceBasePrice;
                @endphp
                <tr>
                    <td>{{ sizeof($items['services']) + sizeof($items['packages']) + 1 }}</td>
                    <td>Distance Charge</td>
                    <td>-</td>
                    <td>₹ {{ number_format($distanceBasePrice,2) }}</td>
                    <td>1</td>
                    <td>₹ {{ number_format($distanceBasePrice,2) }}</td>
                    <td>18</td>
                    <td>₹ {{ number_format($distanceTaxAmount,2) }}</td>
                    <td>₹ {{ number_format($distanceWithTax,2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="totals-table" style="margin-top:20px;">
        @php
            $wallet = (float) $items['wallet'];
            $discount = (float) $items['discount'];
            $grandTotal = (float) $items['grandTotal'];
        @endphp

        @if($discount == 0.00)
            @if($wallet == 0.00)
                <tr>
                    <td><strong>Total</strong></td>
                    <td>₹ {{ number_format($grandTotal, 2) }}</td>
                </tr>
            @else
                <tr>
                    <td><strong>Total<br>Paid from Wallet<br>Grand Total</strong></td>
                    <td>
                        ₹ {{ number_format($wallet + $grandTotal, 2) }}<br>
                        ₹ {{ number_format($wallet, 2) }}<br>
                        ₹ {{ number_format($grandTotal, 2) }}
                    </td>
                </tr>
            @endif
        @else
            @if($wallet == 0.00)
                <tr>
                    <td><strong>Total<br>Discount<br>Grand Total</strong></td>
                    <td>
                        ₹ {{ number_format($grandTotal + $discount, 2) }}<br>
                        ₹ {{ number_format($discount, 2) }}<br>
                        ₹ {{ number_format($grandTotal, 2) }}
                    </td>
                </tr>
            @else
                <tr>
                    <td><strong>Total<br>Paid from Wallet<br>Discount<br>Grand Total</strong></td>
                    <td>
                        ₹ {{ number_format($grandTotal + $discount + $wallet, 2) }}<br>
                        ₹ {{ number_format($wallet, 2) }}<br>
                        ₹ {{ number_format($discount, 2) }}<br>
                        ₹ {{ number_format($grandTotal, 2) }}
                    </td>
                </tr>
            @endif
        @endif
    </table>

    <div class="amount-in-words">
        Amount in Words: <strong>{{ $items['total_in_words'] }}</strong>
    </div>

    {{-- <p><strong>Whether tax is payable under reverse charge:</strong> Yes/No</p> --}}

    <div class="signature">
        For {{ $sellerName }}<br>
        Authorised Signatory
    </div>
    <br>
    Digitally Signed by Papa Bear on {{ $invoiceDate }}

    {{-- <div class="page-break"></div>
    
    <!-- First Invoice -->
    <div class="header">
        <div>
            <img src="{{ asset('assets/images/logo_gold.png') }}" class="logo" alt="Papa Bear Logo">
        </div>
        <div>
            <h2>Pappa Bear</h2>
            <p>GSTIN: {{ $papabearGST }}</p>
            <p>PAN: {{ $papabearPAN }}</p>
            <p>{{ $papabearAddress }}</p>
        </div>
    </div>

    <div class="invoice-title">
        TAX INVOICE
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Billing Address:</strong><br>
                {{ $customerName }}<br>
                {{ $customerAddress }}</td>
            <td><strong>Shipping Address:</strong><br>
                {{ $customerName }}<br>
                {{ $customerAddress }}</td>
            <td>
                <strong>Invoice Number:</strong> {{ $papabearInvoiceNumber }}<br>
                <strong>Date:</strong> {{ $invoiceDate }}<br>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Sl No.</th>
                <th>Descriptions</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Qty</th>
                <th>Net Amount</th>
                <th>Tax Rate (%)</th>
                <th>Tax Type</th>
                <th>Tax Amount</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Commission</td>
                <td>₹10,000</td>
                <td>₹0</td>
                <td>1</td>
                <td>₹10,000</td>
                <td>18%</td>
                <td>SGST/CGST/IGST</td>
                <td>₹1,800</td>
                <td>₹11,800</td>
            </tr>
        </tbody>
    </table>

    <table class="totals-table" style="margin-top:20px;">
        <tr>
            <td><strong>Total</strong></td>
            <td>₹11,800</td>
        </tr>
    </table>

    <div class="amount-in-words">
        Amount in Words: Eleven Thousand Eight Hundred Only
    </div>

    <div class="signature">
        For Pappa Bear<br><br><br>
        Authorised Signatory
    </div> --}}
</body>
</html>
