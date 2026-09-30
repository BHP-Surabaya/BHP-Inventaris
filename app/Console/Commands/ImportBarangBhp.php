<?php

namespace App\Console\Commands;

use App\Imports\BarangImport;
use App\Models\Barang;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('bhp:import-barang {file? : Path ke file Excel UC_PER032}')]
#[Description('Import data referensi barang BMN BHP Surabaya dari Excel UC_PER032')]
class ImportBarangBhp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bhp:import-barang {file? : Path ke file Excel UC_PER032}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data referensi barang BMN BHP Surabaya dari Excel UC_PER032';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filePath = $this->argument('file');

        if (! $filePath) {
            $candidates = [
                storage_path('app/UC_PER032_Referensi_Tabel_Barang_Pers.xlsx'),
                storage_path('app/private/UC_PER032_Referensi_Tabel_Barang_Pers.xlsx'),
                base_path('storage/app/UC_PER032_Referensi_Tabel_Barang_Pers.xlsx'),
                base_path('UC_PER032_Referensi_Tabel_Barang_Pers.xlsx'),
            ];

            foreach ($candidates as $candidate) {
                if (file_exists($candidate)) {
                    $filePath = $candidate;
                    break;
                }
            }
        }

        if (! $filePath || ! file_exists($filePath)) {
            $this->error('File Excel tidak ditemukan.');
            $this->line('Pastikan file berada di: '.storage_path('app/UC_PER032_Referensi_Tabel_Barang_Pers.xlsx'));
            $this->line('Atau jalankan perintah dengan menyertakan path file:');
            $this->line('  php artisan bhp:import-barang "path/to/file.xlsx"');

            return self::FAILURE;
        }

        $this->info('====================================================');
        $this->info('  IMPORT REFERENSI BARANG BMN - BHP SURABAYA (UC_PER032)');
        $this->info('====================================================');
        $this->line("Target File: {$filePath}");
        $this->line('Membaca dan memproses data Excel...');
        $this->newLine();

        $import = new BarangImport;

        try {
            $import->withOutput($this->output)->import($filePath);
        } catch (\Throwable $e) {
            $this->newLine();
            $this->error('Terjadi kesalahan saat mengimpor data: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->newLine(2);
        $this->info('----------------------------------------------------');
        $this->info('Status: Berhasil!');
        $this->line("Data barang diproses : {$import->getImportedCount()} item");
        $this->line("Baris dilewati       : {$import->getSkippedCount()} baris (header/kosong)");
        $this->line('Total barang di DB   : '.Barang::count().' item');
        $this->info('----------------------------------------------------');

        return self::SUCCESS;
    }
}
