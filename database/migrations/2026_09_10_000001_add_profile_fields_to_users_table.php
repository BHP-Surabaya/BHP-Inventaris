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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 30)->nullable()->after('name');
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('pekerjaan', 100)->nullable()->after('phone');
            $table->text('alamat_ktp')->nullable()->after('pekerjaan');
            $table->text('alamat_domisili')->nullable()->after('alamat_ktp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nik', 'phone', 'pekerjaan', 'alamat_ktp', 'alamat_domisili']);
        });
    }
};
