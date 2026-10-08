<?php

namespace Tests\Feature;

use App\Imports\BarangImport;
use App\Models\Barang;
use App\Models\MutasiBarang;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class InventarisTest extends TestCase
{
    use RefreshDatabase;

    public function test_barangs_and_mutasi_barangs_tables_exist_with_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('barangs'));
        $this->assertTrue(Schema::hasColumns('barangs', [
            'id',
            'kd_barang',
            'kd_sub',
            'barcode_key',
            'deskripsi',
            'satuan',
            'lokasi_rak',
            'stok_saldo',
            'min_stok',
            'created_at',
            'updated_at',
        ]));

        $this->assertTrue(Schema::hasTable('mutasi_barangs'));
        $this->assertTrue(Schema::hasColumns('mutasi_barangs', [
            'id',
            'barang_id',
            'user_id',
            'jenis',
            'jumlah',
            'stok_sebelum',
            'stok_sesudah',
            'seksi_pemohon',
            'penerima',
            'no_dokumen',
            'keterangan',
            'created_at',
            'updated_at',
        ]));
    }

    public function test_can_create_barang_and_verify_fillable(): void
    {
        $barang = Barang::create([
            'kd_barang' => '1010301001',
            'kd_sub' => '000001',
            'barcode_key' => '1010301001.000001',
            'deskripsi' => 'Kertas HVS A4 70gr',
            'satuan' => 'Rim',
            'lokasi_rak' => 'Rak A1',
            'stok_saldo' => 50,
            'min_stok' => 10,
        ]);

        $this->assertDatabaseHas('barangs', [
            'id' => $barang->id,
            'barcode_key' => '1010301001.000001',
            'lokasi_rak' => 'Rak A1',
            'stok_saldo' => 50,
            'min_stok' => 10,
        ]);
    }

    public function test_barcode_key_must_be_unique(): void
    {
        Barang::factory()->create(['barcode_key' => '1010301001.000001']);

        $this->expectException(QueryException::class);
        Barang::factory()->create(['barcode_key' => '1010301001.000001']);
    }

    public function test_mutasi_barang_relationships_and_cascade_delete(): void
    {
        $user = User::factory()->create();
        $barang = Barang::factory()->create(['stok_saldo' => 100]);

        $mutasi = MutasiBarang::create([
            'barang_id' => $barang->id,
            'user_id' => $user->id,
            'jenis' => 'KELUAR',
            'jumlah' => 5,
            'stok_sebelum' => 100,
            'stok_sesudah' => 95,
            'seksi_pemohon' => 'Seksi Kurator',
            'penerima' => 'Ahmad',
            'no_dokumen' => 'BHP/OUT/001',
            'keterangan' => 'Keperluan operasional',
        ]);

        $this->assertInstanceOf(Barang::class, $mutasi->barang);
        $this->assertEquals($barang->id, $mutasi->barang->id);

        $this->assertInstanceOf(User::class, $mutasi->user);
        $this->assertEquals($user->id, $mutasi->user->id);

        $this->assertTrue($barang->mutasiBarangs->contains($mutasi));
        $this->assertTrue($user->mutasiBarangs->contains($mutasi));

        // Test cascade delete
        $barang->delete();
        $this->assertDatabaseMissing('mutasi_barangs', ['id' => $mutasi->id]);
    }

    public function test_barang_import_parses_row_and_ignores_headers(): void
    {
        $import = new BarangImport;

        // Baris header harus diabaikan (return null)
        $headerRow = [
            0 => '',
            1 => 'Kd Brng',
            2 => 'Kd Barang',
            3 => '',
            4 => 'Satuan',
            5 => 'Deskripsi',
        ];
        $this->assertNull($import->model($headerRow));

        // Baris kosong harus diabaikan
        $emptyRow = [0 => '', 1 => '', 2 => '', 3 => '', 4 => '', 5 => ''];
        $this->assertNull($import->model($emptyRow));

        // Baris data valid harus disimpan dan di-pad ke 6 digit
        $validRow = [
            0 => '',
            1 => '1',
            2 => '1010301001',
            3 => '',
            4 => 'Buah',
            5 => 'Bolpoint Faster C6',
        ];
        $model = $import->model($validRow);

        $this->assertNotNull($model);
        $this->assertDatabaseHas('barangs', [
            'kd_barang' => '1010301001',
            'kd_sub' => '000001',
            'barcode_key' => '1010301001.000001',
            'deskripsi' => 'Bolpoint Faster C6',
            'satuan' => 'Buah',
        ]);

        // Uji updateOrCreate jika ada pembaruan deskripsi
        $updateRow = [
            0 => '',
            1 => '000001',
            2 => '1010301001',
            3 => '',
            4 => 'Pcs',
            5 => 'Bolpoint Faster C6 Updated',
        ];
        $updatedModel = $import->model($updateRow);
        $this->assertEquals('Bolpoint Faster C6 Updated', $updatedModel->fresh()->deskripsi);
        $this->assertEquals('Pcs', $updatedModel->fresh()->satuan);
    }

    public function test_artisan_import_barang_command(): void
    {
        $this->artisan('bhp:import-barang')
            ->assertSuccessful();

        $this->assertDatabaseHas('barangs', [
            'barcode_key' => '1010301001.000001',
        ]);
    }

    public function test_artisan_import_barang_fails_when_file_not_found(): void
    {
        $this->artisan('bhp:import-barang', ['file' => 'non_existent_file.xlsx'])
            ->assertFailed();
    }

    public function test_inventaris_index_searches_and_paginates(): void
    {
        $user = User::factory()->create();
        Barang::factory()->count(25)->create(['deskripsi' => 'Buku Folio Bergaris']);
        $targetBarang = Barang::factory()->create([
            'deskripsi' => 'Spidol Permanen Khusus',
            'barcode_key' => '9999999999.000099',
        ]);

        $response = $this->actingAs($user)->get(route('inventaris.index'));
        $response->assertOk();
        $response->assertViewHas('barangs');

        // Test search by description
        $searchResponse = $this->actingAs($user)->get(route('inventaris.index', ['search' => 'Spidol Permanen']));
        $searchResponse->assertOk();
        $searchResponse->assertSee('Spidol Permanen Khusus');

        // Test search via JSON API
        $jsonResponse = $this->actingAs($user)->getJson(route('inventaris.index', ['search' => '9999999999.000099']));
        $jsonResponse->assertOk();
        $jsonResponse->assertJsonFragment(['barcode_key' => '9999999999.000099']);
    }

    public function test_root_redirects_to_inventaris(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/dashboard');
    }

    public function test_store_masuk_updates_stock_and_creates_mutation(): void
    {
        $user = User::factory()->create();
        $barang = Barang::factory()->create([
            'stok_saldo' => 10,
            'lokasi_rak' => 'Gudang Utama',
        ]);

        $response = $this->actingAs($user)->postJson(route('inventaris.masuk'), [
            'barang_id' => $barang->id,
            'jumlah' => 15,
            'no_dokumen' => 'BAST-001/BHP/2026',
            'lokasi_rak' => 'Rak B2',
            'keterangan' => 'Pengadaan rutin ATK',
        ]);

        $response->assertCreated();
        $this->assertEquals(25, $barang->fresh()->stok_saldo);
        $this->assertEquals('Rak B2', $barang->fresh()->lokasi_rak);

        $this->assertDatabaseHas('mutasi_barangs', [
            'barang_id' => $barang->id,
            'user_id' => $user->id,
            'jenis' => 'MASUK',
            'jumlah' => 15,
            'stok_sebelum' => 10,
            'stok_sesudah' => 25,
            'no_dokumen' => 'BAST-001/BHP/2026',
        ]);
    }

    public function test_store_keluar_decreases_stock_when_sufficient(): void
    {
        $user = User::factory()->create();
        $barang = Barang::factory()->create([
            'stok_saldo' => 30,
        ]);

        $response = $this->actingAs($user)->postJson(route('inventaris.keluar'), [
            'barang_id' => $barang->id,
            'jumlah' => 10,
            'seksi_pemohon' => 'Seksi Harta Peninggalan',
            'penerima' => 'Budi Santoso',
            'keterangan' => 'Untuk inventaris berkas kurasi',
        ]);

        $response->assertCreated();
        $this->assertEquals(20, $barang->fresh()->stok_saldo);

        $this->assertDatabaseHas('mutasi_barangs', [
            'barang_id' => $barang->id,
            'user_id' => $user->id,
            'jenis' => 'KELUAR',
            'jumlah' => 10,
            'stok_sebelum' => 30,
            'stok_sesudah' => 20,
            'seksi_pemohon' => 'Seksi Harta Peninggalan',
            'penerima' => 'Budi Santoso',
        ]);
    }

    public function test_store_keluar_rejects_when_stock_insufficient(): void
    {
        $user = User::factory()->create();
        $barang = Barang::factory()->create([
            'stok_saldo' => 5,
        ]);

        $response = $this->actingAs($user)->postJson(route('inventaris.keluar'), [
            'barang_id' => $barang->id,
            'jumlah' => 10,
            'seksi_pemohon' => 'Seksi Kurator',
            'penerima' => 'Rina',
            'keterangan' => 'Permintaan melebihi stok',
        ]);

        $response->assertStatus(422);
        $this->assertEquals(5, $barang->fresh()->stok_saldo);
        $this->assertDatabaseMissing('mutasi_barangs', [
            'barang_id' => $barang->id,
            'jenis' => 'KELUAR',
        ]);
    }

    public function test_print_label_renders_view_with_barang(): void
    {
        $user = User::factory()->create();
        $barang = Barang::factory()->create([
            'deskripsi' => 'Map Gantung Arsip',
            'barcode_key' => '1010301001.000045',
        ]);

        $response = $this->actingAs($user)->get(route('inventaris.print', $barang));
        $response->assertOk();
        $response->assertViewIs('inventaris.print_label');
        $response->assertViewHas('barang', $barang);
        $response->assertSee('1010301001.000045');
        $response->assertSee('Map Gantung Arsip');
    }

    public function test_api_barang_lookup_by_barcode_key(): void
    {
        $barang = Barang::factory()->create([
            'barcode_key' => '1010301001.000088',
            'deskripsi' => 'Buku Kas Pembantu BHP',
            'stok_saldo' => 12,
        ]);

        $response = $this->getJson("/api/barang/{$barang->barcode_key}");

        $response->assertOk();
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'id' => $barang->id,
                'barcode_key' => '1010301001.000088',
                'deskripsi' => 'Buku Kas Pembantu BHP',
                'stok_saldo' => 12,
            ],
        ]);
    }

    public function test_api_barang_lookup_returns_404_when_not_found(): void
    {
        $response = $this->getJson('/api/barang/0000000000.999999');

        $response->assertNotFound();
        $response->assertJson([
            'status' => 'error',
        ]);
    }
}
