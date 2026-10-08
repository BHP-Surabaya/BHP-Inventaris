<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\BonBarang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BonBarangTest extends TestCase
{
    use RefreshDatabase;

    public function test_pegawai_gudang_can_access_bon_page(): void
    {
        $user = User::factory()->create(['role' => 'pegawai_gudang']);

        $response = $this->actingAs($user)->get(route('bon.index'));

        $response->assertStatus(200);
        $response->assertSee('Bon Pengeluaran Barang');
    }

    public function test_admin_can_access_bon_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('bon.index'));

        $response->assertStatus(200);
    }

    public function test_scan_barcode_endpoint_returns_item(): void
    {
        $user = User::factory()->create(['role' => 'pegawai_gudang']);
        $barang = Barang::factory()->create([
            'barcode_key' => '1010301001.000001',
            'deskripsi' => 'Bolpoint Faster C6',
            'stok_saldo' => 50,
        ]);

        $response = $this->actingAs($user)->postJson(route('bon.scan'), [
            'code' => '1010301001.000001',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('barang.deskripsi', 'Bolpoint Faster C6');
    }

    public function test_can_create_bon_and_decrease_stock(): void
    {
        $user = User::factory()->create(['role' => 'pegawai_gudang']);
        $barang = Barang::factory()->create([
            'stok_saldo' => 20,
            'satuan' => 'Buah',
        ]);

        $postData = [
            'no_bon' => 'BON-202610-0001',
            'tanggal' => now()->format('Y-m-d'),
            'nama_pemohon' => 'Budi Santoso',
            'seksi_pemohon' => 'Seksi Kurator',
            'keperluan' => 'Kebutuhan Sidang',
            'items' => [
                [
                    'barang_id' => $barang->id,
                    'jumlah' => 5,
                ],
            ],
        ];

        $response = $this->actingAs($user)->post(route('bon.store'), $postData);

        $response->assertRedirect(route('bon.index'));
        $this->assertDatabaseHas('bon_barangs', [
            'no_bon' => 'BON-202610-0001',
            'nama_pemohon' => 'Budi Santoso',
            'total_item' => 5,
        ]);

        $barang->refresh();
        $this->assertEquals(15, $barang->stok_saldo);

        $this->assertDatabaseHas('mutasi_barangs', [
            'jenis' => 'KELUAR',
            'jumlah' => 5,
            'penerima' => 'Budi Santoso',
        ]);
    }

    public function test_can_view_print_bon(): void
    {
        $user = User::factory()->create(['role' => 'pegawai_gudang']);
        $bon = BonBarang::create([
            'no_bon' => 'BON-202610-0099',
            'tanggal' => now(),
            'nama_pemohon' => 'Rina',
            'seksi_pemohon' => 'Tata Usaha',
            'user_id' => $user->id,
            'total_item' => 2,
        ]);

        $response = $this->actingAs($user)->get(route('bon.print', $bon->id));

        $response->assertStatus(200);
        $response->assertSee('BON-202610-0099');
        $response->assertSee('SURAT BUKTI PENGELUARAN BARANG');
    }
}
