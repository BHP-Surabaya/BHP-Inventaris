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
        Schema::create('bon_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('no_bon', 50)->unique();
            $table->date('tanggal');
            $table->string('nama_pemohon', 100);
            $table->string('seksi_pemohon', 100);
            $table->text('keperluan')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->integer('total_item')->default(0);
            $table->string('status', 30)->default('SELESAI');
            $table->timestamps();
        });

        Schema::create('bon_barang_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_barang_id')->constrained('bon_barangs')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->string('satuan', 30);
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_barang_items');
        Schema::dropIfExists('bon_barangs');
    }
};
