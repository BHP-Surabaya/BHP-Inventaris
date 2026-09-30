<?php

namespace App\Imports;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BarangImport implements ToModel, WithProgressBar, WithStartRow
{
    use Importable;

    /**
     * Jumlah baris data barang yang berhasil diimpor atau diperbarui.
     */
    private int $importedCount = 0;

    /**
     * Jumlah baris header / judul berulang / baris kosong yang dilewati.
     */
    private int $skippedCount = 0;

    /**
     * Mulai membaca dari baris ke-5 untuk melewati header UAKPB.
     */
    public function startRow(): int
    {
        return 5;
    }

    /**
     * Mapping kolom Excel ke model Barang.
     *
     * Kolom B (index 1): Kd Brng (sub kode)
     * Kolom C (index 2): Kd Barang (10 digit klasifikasi)
     * Kolom E (index 4): Satuan
     * Kolom F (index 5): Deskripsi
     *
     * @param  array<int, mixed>  $row
     */
    public function model(array $row): ?Model
    {
        $kdSubRaw = isset($row[1]) ? trim((string) $row[1]) : '';
        $kdBarang = isset($row[2]) ? trim((string) $row[2]) : '';
        $satuan = isset($row[4]) ? trim((string) $row[4]) : '';
        $deskripsi = isset($row[5]) ? trim((string) $row[5]) : '';

        // Filter dan abaikan baris kosong atau baris judul berulang / non-numerik
        if ($kdSubRaw === '' || $kdBarang === '' || ! is_numeric($kdSubRaw) || ! is_numeric($kdBarang)) {
            $this->skippedCount++;

            return null;
        }

        // Format sub kode dengan padding 6 digit
        $kdSubPadded = str_pad($kdSubRaw, 6, '0', STR_PAD_LEFT);
        $barcodeKey = $kdBarang.'.'.$kdSubPadded;

        $this->importedCount++;

        return Barang::updateOrCreate(
            ['barcode_key' => $barcodeKey],
            [
                'kd_barang' => $kdBarang,
                'kd_sub' => $kdSubPadded,
                'deskripsi' => $deskripsi,
                'satuan' => $satuan,
            ]
        );
    }

    /**
     * Ambil total data yang berhasil diimpor.
     */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    /**
     * Ambil total baris yang dilewati.
     */
    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }
}
