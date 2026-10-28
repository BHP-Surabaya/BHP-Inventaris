<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PembelianTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_pembelian_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('pembelian.index'));

        $response->assertStatus(200);
        $response->assertSee('Catat Pembelian');
        $response->assertSee('Pusat pencatatan faktur barang masuk');
        $response->assertViewHas('daftarBarang');
        $response->assertViewHas('daftarKdBarang');
    }

    public function test_non_admin_cannot_access_pembelian_page(): void
    {
        $pegawai = User::factory()->create(['role' => 'pegawai_gudang']);

        $response = $this->actingAs($pegawai)->get(route('pembelian.index'));

        $response->assertStatus(403);
    }

    public function test_next_subkode_endpoint_returns_first_sequence_for_new_prefix(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->getJson(route('pembelian.next-subkode', ['kd_barang' => '1010305002']));

        $response->assertStatus(200)
            ->assertJson([
                'kd_barang' => '1010305002',
                'next_kd_sub' => '000001',
                'full_kode' => '1010305002.000001',
                'total_existing' => 0,
            ]);
    }

    public function test_next_subkode_endpoint_increments_sequence_for_existing_items(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Barang::factory()->create([
            'kd_barang' => '1010305002',
            'kd_sub' => '000017',
            'barcode_key' => '1010305002.000017',
        ]);

        // Request with trailing dot should also be cleaned properly
        $response = $this->actingAs($admin)->getJson(route('pembelian.next-subkode', ['kd_barang' => '1010305002.']));

        $response->assertStatus(200)
            ->assertJson([
                'kd_barang' => '1010305002',
                'next_kd_sub' => '000018',
                'full_kode' => '1010305002.000018',
                'total_existing' => 1,
            ]);
    }

    public function test_admin_can_record_pembelian_for_existing_barang(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $barang = Barang::factory()->create([
            'deskripsi' => 'Kertas HVS F4 70gr',
            'stok_saldo' => 15,
            'satuan' => 'Rim',
        ]);

        $response = $this->actingAs($admin)->post(route('pembelian.store'), [
            'tipe_barang' => 'eksisting',
            'barang_id' => $barang->id,
            'jumlah' => 10,
            'no_dokumen' => 'FKT-2026/10/01',
            'nama_vendor' => 'CV. Berkah Sinar',
            'lokasi_rak' => 'Rak A-01',
            'keterangan' => 'Pengadaan rutin DIPA',
        ]);

        $response->assertRedirect(route('pembelian.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('barangs', [
            'id' => $barang->id,
            'stok_saldo' => 25,
            'lokasi_rak' => 'Rak A-01',
        ]);

        $this->assertDatabaseHas('mutasi_barangs', [
            'barang_id' => $barang->id,
            'user_id' => $admin->id,
            'jenis' => 'MASUK',
            'jumlah' => 10,
            'stok_sebelum' => 15,
            'stok_sesudah' => 25,
            'no_dokumen' => 'FKT-2026/10/01',
        ]);
    }

    public function test_admin_can_record_pembelian_for_new_barang_with_auto_generated_kd_sub(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Item 17 exists
        Barang::factory()->create([
            'kd_barang' => '1010305002',
            'kd_sub' => '000017',
            'barcode_key' => '1010305002.000017',
        ]);

        // Purchase new item under prefix 1010305002
        $response = $this->actingAs($admin)->post(route('pembelian.store'), [
            'tipe_barang' => 'baru',
            'kd_barang' => '1010305002',
            'deskripsi' => 'Tinta Printer Epson 003 Black',
            'kategori' => 'ATK & Kertas',
            'spesifikasi' => '65ml Original',
            'satuan' => 'Botol',
            'min_stok' => 5,
            'jumlah' => 18,
            'no_dokumen' => 'FKT-2026/10/05',
            'nama_vendor' => 'CV. Sumber Rejeki IT',
            'lokasi_rak' => 'Rak IT-03',
            'keterangan' => 'Stok baru tinta printer kurator',
        ]);

        $response->assertRedirect(route('pembelian.index'));
        $response->assertSessionHas('success');

        // Verify new barang was created with kd_sub 000018
        $this->assertDatabaseHas('barangs', [
            'kd_barang' => '1010305002',
            'kd_sub' => '000018',
            'barcode_key' => '1010305002.000018',
            'deskripsi' => 'Tinta Printer Epson 003 Black',
            'satuan' => 'Botol',
            'stok_saldo' => 18,
            'lokasi_rak' => 'Rak IT-03',
        ]);

        $newBarang = Barang::where('barcode_key', '1010305002.000018')->firstOrFail();

        // Verify mutasi record
        $this->assertDatabaseHas('mutasi_barangs', [
            'barang_id' => $newBarang->id,
            'user_id' => $admin->id,
            'jenis' => 'MASUK',
            'jumlah' => 18,
            'stok_sebelum' => 0,
            'stok_sesudah' => 18,
            'no_dokumen' => 'FKT-2026/10/05',
        ]);
    }
}
