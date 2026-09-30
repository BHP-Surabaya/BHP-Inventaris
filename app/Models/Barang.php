<?php

namespace App\Models;

use Database\Factories\BarangFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    /** @use HasFactory<BarangFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'barangs';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kd_barang',
        'kd_sub',
        'barcode_key',
        'barcode',
        'deskripsi',
        'kategori',
        'spesifikasi',
        'satuan',
        'lokasi_rak',
        'stok_saldo',
        'min_stok',
        'status_khusus',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'stok_saldo' => 'integer',
            'min_stok' => 'integer',
        ];
    }

    /**
     * Relasi hasMany ke MutasiBarang.
     *
     * @return HasMany<MutasiBarang, $this>
     */
    public function mutasiBarangs(): HasMany
    {
        return $this->hasMany(MutasiBarang::class, 'barang_id');
    }

    /**
     * Accessor untuk kategori dengan fallback cerdas.
     */
    public function getKategoriLabelAttribute(): string
    {
        if (!empty($this->kategori)) {
            return $this->kategori;
        }

        $desk = strtolower($this->deskripsi);
        if (str_contains($desk, 'kertas') || str_contains($desk, 'hvs') || str_contains($desk, 'bolpoint') || str_contains($desk, 'spidol') || str_contains($desk, 'buku') || str_contains($desk, 'map')) {
            return 'ATK & Kertas';
        } elseif (str_contains($desk, 'laptop') || str_contains($desk, 'komputer') || str_contains($desk, 'printer') || str_contains($desk, 'toner') || str_contains($desk, 'mouse') || str_contains($desk, 'flashdisk')) {
            return 'Elektronik & IT';
        }

        return 'Peralatan Kantor';
    }

    /**
     * Accessor untuk status label & badge styling.
     */
    public function getStatusDataAttribute(): array
    {
        if (!empty($this->status_khusus)) {
            return [
                'text' => $this->status_khusus,
                'class' => 'bg-rose-50 text-rose-700 border-rose-200',
                'dot' => 'bg-rose-500',
            ];
        }

        if ($this->stok_saldo <= 0) {
            return [
                'text' => 'Stok Habis',
                'class' => 'bg-rose-50 text-rose-700 border-rose-200',
                'dot' => 'bg-rose-500',
            ];
        }

        if ($this->stok_saldo <= $this->min_stok || $this->stok_saldo < 10) {
            return [
                'text' => 'Menipis',
                'class' => 'bg-amber-50 text-amber-700 border-amber-200',
                'dot' => 'bg-amber-500',
            ];
        }

        return [
            'text' => 'Stok Aman',
            'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'dot' => 'bg-emerald-500',
        ];
    }
}
