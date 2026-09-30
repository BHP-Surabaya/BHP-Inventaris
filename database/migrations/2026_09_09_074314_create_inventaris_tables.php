<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kd_barang', 10);
            $table->string('kd_sub', 6);
            $table->string('barcode_key', 20)->unique();
            $table->string('deskripsi', 150);
            $table->string('satuan', 30);
            $table->string('lokasi_rak', 50)->default('Gudang Utama');
            $table->integer('stok_saldo')->default(0);
            $table->integer('min_stok')->default(5);
            $table->timestamps();

            $table->index(['kd_barang', 'kd_sub']);
        });

        Schema::create('mutasi_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('jenis', ['MASUK', 'KELUAR']);
            $table->integer('jumlah');
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');
            $table->string('seksi_pemohon')->nullable();
            $table->string('penerima')->nullable();
            $table->string('no_dokumen')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_barangs');
        Schema::dropIfExists('barangs');
    }
};
