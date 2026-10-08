<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti_Bon_{{ $bon->no_bon }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
            background: #fff;
            padding: 20px;
            font-size: 11pt;
            line-height: 1.4;
        }

        .header-kop {
            display: flex;
            align-items: center;
            border-bottom: 2.5px solid #000;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }

        .header-kop img {
            width: 70px;
            height: auto;
            margin-right: 18px;
        }

        .header-text {
            text-align: center;
            flex: 1;
        }

        .header-text h3 {
            font-size: 11pt;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-text h2 {
            font-size: 13pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .header-text p {
            font-size: 8.5pt;
            color: #333;
            margin-top: 2px;
        }

        .title-doc {
            text-align: center;
            margin: 15px 0 20px;
        }

        .title-doc h1 {
            font-size: 13pt;
            font-weight: 900;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .title-doc span {
            font-size: 10.5pt;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 18px;
            font-size: 10pt;
        }

        .meta-table td {
            padding: 3px 6px;
            vertical-align: top;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 10pt;
        }

        .table-data th, .table-data td {
            border: 1px solid #000;
            padding: 7px 10px;
        }

        .table-data th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9.5pt;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .ttd-container {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            font-size: 10pt;
            page-break-inside: avoid;
        }

        .ttd-box {
            text-align: center;
            width: 45%;
        }

        .ttd-space {
            height: 65px;
        }

        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .btn-print {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #2563eb;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        @media print {
            .btn-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- KOP SURAT RESMI -->
    <div class="header-kop">
        <img src="{{ asset('images/logo-pengayoman.svg') }}" alt="Logo Kemenkumham">
        <div class="header-text">
            <h3>KEMENTERIAN HUKUM DAN HAK ASASI MANUSIA RI</h3>
            <h3>KANTOR WILAYAH JAWA TIMUR</h3>
            <h2>BALAI HARTA PENINGGALAN SURABAYA</h2>
            <p>Jalan Mayjen Sungkono No. 89, Surabaya | Telp/Fax: (031) 5678901 | Website: bhp.kemenkumham.go.id</p>
        </div>
    </div>

    <!-- JUDUL DOKUMEN -->
    <div class="title-doc">
        <h1>SURAT BUKTI PENGELUARAN BARANG (BON ATK)</h1>
        <span>Nomor: {{ $bon->no_bon }}</span>
    </div>

    <!-- META DATA PEMOHON -->
    <table class="meta-table">
        <tr>
            <td style="width: 18%;"><strong>Tanggal Bon</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 40%;">{{ $bon->tanggal->translatedFormat('d F Y') }}</td>
            <td style="width: 18%;"><strong>Petugas Gudang</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 20%;">{{ $bon->user->name ?? 'Petugas Persediaan' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Pemohon</strong></td>
            <td>:</td>
            <td>{{ $bon->nama_pemohon }}</td>
            <td><strong>Status</strong></td>
            <td>:</td>
            <td><strong style="color: green;">{{ $bon->status }}</strong></td>
        </tr>
        <tr>
            <td><strong>Seksi / Unit Kerja</strong></td>
            <td>:</td>
            <td>{{ $bon->seksi_pemohon }}</td>
            <td><strong>Keperluan</strong></td>
            <td>:</td>
            <td>{{ $bon->keperluan ?: '-' }}</td>
        </tr>
    </table>

    <!-- TABEL RINCIAN BARANG -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Kode Barcode / BMN</th>
                <th>Nama Barang Persediaan</th>
                <th style="width: 15%;">Lokasi Rak</th>
                <th style="width: 10%;">Jumlah</th>
                <th style="width: 10%;">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bon->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center" style="font-family: monospace; font-weight: bold;">{{ $item->barang->barcode_key ?? '-' }}</td>
                    <td><strong>{{ $item->barang->deskripsi ?? 'Barang Persediaan' }}</strong></td>
                    <td>{{ $item->barang->lokasi_rak ?? '-' }}</td>
                    <td class="text-center"><strong>{{ $item->jumlah }}</strong></td>
                    <td class="text-center">{{ $item->satuan }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="4" class="text-right" style="padding: 8px 10px;">TOTAL BARANG DIKELUARKAN:</td>
                <td class="text-center" style="padding: 8px 10px;">{{ $bon->total_item }}</td>
                <td class="text-center">Unit/Pcs</td>
            </tr>
        </tfoot>
    </table>

    <!-- TANDA TANGAN SERAH TERIMA -->
    <div class="ttd-container">
        <div class="ttd-box">
            <p>Yang Menerima,</p>
            <p style="color: #555; font-size: 9pt;">Pegawai / Pemohon</p>
            <div class="ttd-space"></div>
            <p class="ttd-name">{{ $bon->nama_pemohon }}</p>
            <p style="font-size: 9pt; color: #555;">{{ $bon->seksi_pemohon }}</p>
        </div>

        <div class="ttd-box">
            <p>Surabaya, {{ $bon->tanggal->translatedFormat('d F Y') }}</p>
            <p>Yang Menyerahkan,</p>
            <p style="color: #555; font-size: 9pt;">Pengurus Barang / Petugas Gudang</p>
            <div class="ttd-space"></div>
            <p class="ttd-name">{{ $bon->user->name ?? 'Petugas Gudang BHP' }}</p>
            <p style="font-size: 9pt; color: #555;">NIP: ............................................</p>
        </div>
    </div>

    <button onclick="window.print()" class="btn-print">🖨️ Cetak Lembar Bon</button>

</body>
</html>
