<!DOCTYPE html>
<html>
<head>
    <title>Invoice - {{ $invoiceNumber }}</title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .button-container {
            margin: 20px 0;
            text-align: center;
        }
        .download-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #3490dc;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
        }

        .a4-preview {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: auto;
            background: white;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        @media print {
            body {
                margin: 0;
            }
            .a4-preview {
                box-shadow: none;
                page-break-after: always;
            }
            .download-button {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="button-container">
            <a class="download-button" href="{{ request()->fullUrlWithQuery(['type' => 'download']) }}">Download Invoice</a>
        </div>
        <div class="a4-preview">{!! $pdf !!}</div>
    </div>
</body>
</html>
