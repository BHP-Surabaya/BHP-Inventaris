<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\MutasiBarang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MutasiBarang>
 */
class MutasiBarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jumlah = fake()->numberBetween(1, 20);
        $stokSebelum = fake()->numberBetween(20, 100);
        $stokSesudah = $stokSebelum + $jumlah;

        return [
            'barang_id' => Barang::factory(),
            'user_id' => User::factory(),
            'jenis' => 'MASUK',
            'jumlah' => $jumlah,
            'stok_sebelum' => $stokSebelum,
            'stok_sesudah' => $stokSesudah,
            'seksi_pemohon' => fake()->randomElement(['Sub Bagian Umum', 'Seksi Harta Peninggalan', 'Seksi Kurator']),
            'penerima' => fake()->name(),
            'no_dokumen' => 'BHP-DOC-'.fake()->numerify('####'),
            'keterangan' => fake()->sentence(),
        ];
    }
}
