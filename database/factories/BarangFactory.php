<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kdBarang = fake()->numerify('##########');
        $kdSub = fake()->numerify('######');

        return [
            'kd_barang' => $kdBarang,
            'kd_sub' => $kdSub,
            'barcode_key' => "{$kdBarang}.{$kdSub}",
            'deskripsi' => fake()->words(3, true),
            'satuan' => fake()->randomElement(['Rim', 'Pcs', 'Dus', 'Kotak', 'Pak', 'Buah']),
            'lokasi_rak' => 'Gudang Utama',
            'stok_saldo' => fake()->numberBetween(10, 100),
            'min_stok' => 5,
        ];
    }
}
