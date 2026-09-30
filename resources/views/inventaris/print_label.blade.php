<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label_{{ $barang->barcode_key }}</title>
    @php
        $widthMm = $w ?? 75;
        $heightMm = $h ?? 50;
        $isTall = $heightMm >= 35;
    @endphp
    <style>
        @page {
            size: {{ $widthMm }}mm {{ $heightMm }}mm;
            margin: 0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            width: {{ $widthMm }}mm;
            height: {{ $heightMm }}mm;
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #000000;
            font-family: Arial, Helvetica, sans-serif;
            overflow: hidden;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .label-container {
            width: {{ $widthMm }}mm;
            height: {{ $heightMm }}mm;
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: {{ $isTall ? '2.5mm' : '1mm 1.5mm' }};
            border: 1px solid #000000;
            overflow: hidden;
        }

        .col-left {
            width: {{ $isTall ? '32mm' : '17mm' }};
            min-width: {{ $isTall ? '32mm' : '17mm' }};
            max-width: {{ $isTall ? '32mm' : '17mm' }};
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-right: 1px solid #000000;
            padding-right: 1.5mm;
        }

        .col-left svg {
            width: 100%;
            height: auto;
            max-height: {{ $isTall ? '32mm' : '15mm' }};
            display: block;
        }

        .col-left .code-caption {
            font-family: 'Courier New', Courier, monospace;
            font-size: {{ $isTall ? '6.5pt' : '5pt' }};
            font-weight: bold;
            margin-top: 1mm;
            text-align: center;
            white-space: nowrap;
        }

        .col-right {
            flex: 1;
            height: 100%;
            padding-left: 2mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .header-instansi {
            font-size: {{ $isTall ? '7.5pt' : '6pt' }};
            font-weight: 900;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            border-bottom: 0.8px solid #000000;
            padding-bottom: 0.8mm;
            line-height: 1.1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .kode-barang {
            font-family: 'Courier New', Courier, monospace;
            font-size: {{ $isTall ? '8pt' : '6.5pt' }};
            font-weight: bold;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nama-barang {
            font-size: {{ $isTall ? '9pt' : '6.8pt' }};
            font-weight: bold;
            line-height: 1.2;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: {{ $isTall ? '3' : '1' }};
            -webkit-box-orient: vertical;
        }

        .metadata {
            font-size: {{ $isTall ? '7pt' : '5.5pt' }};
            font-weight: 600;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media screen {
            body {
                background: #e2e8f0;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }

            .label-container {
                background: #ffffff;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
                outline: 1px dashed #94a3b8;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="label-container">
        <!-- Kolom Kiri: QR Code -->
        <div class="col-left">
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size($isTall ? 110 : 58)->margin(0)->generate($barang->barcode_key) !!}
            <div class="code-caption">{{ $barang->barcode_key }}</div>
        </div>

        <!-- Kolom Kanan: Teks & Informasi BMN -->
        <div class="col-right">
            <!-- Baris 1: Header Instansi -->
            <div class="header-instansi">BHP SURABAYA - PERSEDIAAN</div>

            <!-- Baris 2: Nama Barang -->
            <div class="nama-barang" title="{{ $barang->deskripsi }}">{{ $barang->deskripsi }}</div>

            <!-- Baris 3: Kode Barang -->
            <div class="kode-barang">Kode: {{ $barang->barcode_key }}</div>

            <!-- Baris 4: Lokasi Rak & Metadata -->
            <div class="metadata">Rak: {{ $barang->lokasi_rak }}</div>
            <div class="metadata">Satuan: {{ $barang->satuan }} | Kondisi: Baik</div>
        </div>
    </div>
</body>
</html>
