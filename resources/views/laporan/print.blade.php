<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judulLaporan }} - Balai Harta Peninggalan Surabaya</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
        }

        body {
            background-color: #f1f5f9;
            color: #000;
            font-size: 11pt;
            line-height: 1.3;
        }

        .no-print-bar {
            background: #0f172a;
            color: white;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            font-family: system-ui, -apple-system, sans-serif;
        }

        .btn-print {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print:hover {
            background: #1d4ed8;
        }

        .btn-close {
            background: #475569;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm 20mm;
            margin: 20px auto;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* KOP SURAT DINAS */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            text-align: center;
            margin-bottom: 2px;
        }

        .kop-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .kop-text h2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-text h1 {
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-text p {
            font-size: 9pt;
            margin-top: 2px;
            font-style: italic;
        }

        .kop-divider {
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 4px;
            margin: 6px 0 16px 0;
        }

        /* JUDUL LAPORAN */
        .judul-laporan {
            text-align: center;
            margin-bottom: 14px;
        }

        .judul-laporan h3 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .judul-laporan .periode {
            font-size: 10pt;
            margin-top: 4px;
            font-style: italic;
        }

        /* TABEL RESMI */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9.5pt;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: middle;
        }

        table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8.5pt;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }

        /* LEMBAR TANDA TANGAN */
        .ttd-container {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .ttd-box {
            text-align: center;
            width: 45%;
            font-size: 10pt;
        }

        .ttd-space {
            height: 65px;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }

        @media print {
            body {
                background: white;
            }
            .no-print-bar {
                display: none !important;
            }
            .page {
                box-shadow: none;
                margin: 0;
                width: 100%;
                padding: 10mm 15mm;
            }
            @page {
                size: A4;
                margin: 10mm;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Kontrol Tombol Print -->
    <div class="no-print-bar">
        <div>
            <strong>Pratinjau Cetak Lembar Resmi</strong> &mdash; Balai Harta Peninggalan Surabaya
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-print">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak Lembar Resmi / Simpan PDF</span>
            </button>
            <button onclick="window.close()" class="btn-close">Tutup</button>
        </div>
    </div>

    <!-- Halaman Dokumen A4 -->
    <div class="page">
        <!-- KOP SURAT DINAS RESMI -->
        <div class="kop-surat">
            <img src="{{ asset('images/logo-pengayoman.svg') }}" alt="Logo Pengayoman" class="kop-logo">
            <div class="kop-text">
                <h2>KEMENTERIAN HUKUM DAN HAM REPUBLIK INDONESIA</h2>
                <h2>DIREKTORAT JENDERAL ADMINISTRASI HUKUM UMUM</h2>
                <h1>BALAI HARTA PENINGGALAN SURABAYA</h1>
                <p>Jl. Medokan Semampir Indah No. 97, Sukolilo, Surabaya, Jawa Timur 60119 | Telp: (031) 5923985</p>
            </div>
        </div>
        <div class="kop-divider"></div>

        <!-- JUDUL LAPORAN -->
        <div class="judul-laporan">
            <h3>{{ $judulLaporan }}</h3>
            @if ($jenis !== 'stok')
                <div class="periode">
                    Periode: {{ date('d F Y', strtotime($dariTanggal)) }} s/d {{ date('d F Y', strtotime($sampaiTanggal)) }}
                </div>
            @else
                <div class="periode">
                    Kondisi Per Tanggal: {{ date('d F Y') }} &bull; Kategori: {{ ucfirst($selectedKategori) }}
                </div>
            @endif
        </div>

        <!-- TABEL DATA -->
        <table>
            @if ($jenis === 'stok')
                <thead>
                    <tr>
                        <th style="width: 25px;">No</th>
                        <th style="width: 110px;">Kode BMN</th>
                        <th>Nama Barang (Deskripsi)</th>
                        <th style="width: 75px;">Kategori</th>
                        <th style="width: 80px;">Lokasi Rak</th>
                        <th style="width: 55px;">Saldo</th>
                        <th style="width: 50px;">Satuan</th>
                        <th style="width: 55px;">Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataLaporan as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td class="font-mono text-center">{{ $item->barcode_key }}</td>
                            <td>{{ $item->deskripsi }}</td>
                            <td class="text-center">{{ $item->kategori ?? 'Umum' }}</td>
                            <td class="text-center">{{ $item->lokasi_rak }}</td>
                            <td class="text-right" style="font-weight: bold;">{{ number_format($item->stok_saldo) }}</td>
                            <td class="text-center">{{ $item->satuan }}</td>
                            <td class="text-center">{{ $item->stok_saldo <= $item->min_stok ? 'Kritis' : 'Baik' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada data barang yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            @elseif ($jenis === 'masuk')
                <thead>
                    <tr>
                        <th style="width: 25px;">No</th>
                        <th style="width: 100px;">No. Faktur</th>
                        <th style="width: 80px;">Tanggal</th>
                        <th>Nama Barang (BMN)</th>
                        <th style="width: 60px;">Jumlah</th>
                        <th style="width: 50px;">Satuan</th>
                        <th>Vendor / Keterangan</th>
                        <th style="width: 80px;">Penerima</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataLaporan as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td class="font-mono text-center">{{ $item->no_dokumen ?: '-' }}</td>
                            <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>{{ $item->barang?->deskripsi ?? 'Barang Telah Dihapus' }}</td>
                            <td class="text-right" style="font-weight: bold;">+{{ $item->jumlah }}</td>
                            <td class="text-center">{{ $item->barang?->satuan ?? 'Unit' }}</td>
                            <td>{{ $item->keterangan ?: '-' }}</td>
                            <td class="text-center">{{ $item->user?->name ?? 'Admin' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada data barang masuk pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            @elseif ($jenis === 'keluar')
                <thead>
                    <tr>
                        <th style="width: 25px;">No</th>
                        <th style="width: 100px;">No. Bon</th>
                        <th style="width: 80px;">Tanggal</th>
                        <th>Nama Barang (BMN)</th>
                        <th style="width: 60px;">Jumlah</th>
                        <th style="width: 50px;">Satuan</th>
                        <th>Seksi Pemohon</th>
                        <th style="width: 80px;">Penerima</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataLaporan as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td class="font-mono text-center">{{ $item->no_dokumen ?: 'BON-'.$item->id }}</td>
                            <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>{{ $item->barang?->deskripsi ?? 'Barang Telah Dihapus' }}</td>
                            <td class="text-right" style="font-weight: bold;">-{{ $item->jumlah }}</td>
                            <td class="text-center">{{ $item->barang?->satuan ?? 'Unit' }}</td>
                            <td>{{ $item->seksi_pemohon ?: 'Seksi Operasional' }}</td>
                            <td class="text-center">{{ $item->penerima ?: ($item->user?->name ?? 'Pegawai BHP') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada data pengeluaran pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            @else
                <thead>
                    <tr>
                        <th style="width: 25px;">No</th>
                        <th style="width: 110px;">No. Berita Acara</th>
                        <th style="width: 80px;">Tanggal Audit</th>
                        <th>Nama Barang (BMN)</th>
                        <th style="width: 60px;">Buku</th>
                        <th style="width: 60px;">Fisik</th>
                        <th style="width: 60px;">Selisih</th>
                        <th>Catatan Audit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataLaporan as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td class="font-mono text-center">{{ $item->no_dokumen ?: 'BA-OPNAME' }}</td>
                            <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>{{ $item->barang?->deskripsi ?? 'Barang Telah Dihapus' }}</td>
                            <td class="text-center">{{ $item->stok_sebelum }}</td>
                            <td class="text-center" style="font-weight: bold;">{{ $item->stok_sesudah }}</td>
                            <td class="text-center" style="font-weight: bold;">
                                {{ $item->jenis === 'MASUK' ? '+' : '-' }}{{ $item->jumlah }}
                            </td>
                            <td>{{ $item->keterangan ?: 'Pencocokan fisik' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 20px;">Belum ada berita acara stock opname pada rentang tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            @endif
        </table>

        <!-- LEMBAR TANDA TANGAN DINAS -->
        <div class="ttd-container">
            <div class="ttd-box">
                <p>Mengetahui,</p>
                <p><strong>Kepala Balai Harta Peninggalan Surabaya</strong></p>
                <div class="ttd-space"></div>
                <p class="ttd-nama">HENDRA ANDRIANTO, S.H., M.H.</p>
                <p>NIP. 19780512 200212 1 001</p>
            </div>

            <div class="ttd-box">
                <p>Surabaya, {{ date('d F Y') }}</p>
                <p><strong>Pengurus Barang Pengguna / Operator BMN</strong></p>
                <div class="ttd-space"></div>
                <p class="ttd-nama">{{ Auth::user()?->name ?? 'FERNANDA' }}</p>
                <p>NIP. 19950814 202012 1 002</p>
            </div>
        </div>
    </div>

</body>
</html>
