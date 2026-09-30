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
        Schema::table('barangs', function (Blueprint $table) {
            $table->string('kategori', 60)->nullable()->default('ATK & Kertas')->after('deskripsi');
            $table->string('spesifikasi', 255)->nullable()->after('kategori');
            $table->string('barcode', 50)->nullable()->after('barcode_key');
            $table->string('status_khusus', 50)->nullable()->after('min_stok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'spesifikasi', 'barcode', 'status_khusus']);
        });
    }
};
