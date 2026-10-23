<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Kurniawan Tri',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'pekerjaan' => 'Pengelola BMN & Gudang Persediaan',
            ]
        );

        User::firstOrCreate(
            ['email' => 'pegawai@gmail.com'],
            [
                'name' => 'Fernanda',
                'password' => Hash::make('admin123'),
                'role' => 'pegawai_gudang',
                'pekerjaan' => 'Pegawai Gudang',
            ]
        );
    }
}
