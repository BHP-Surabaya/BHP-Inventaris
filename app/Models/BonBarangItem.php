<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonBarangItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bon_barang_id',
        'barang_id',
        'jumlah',
        'satuan',
        'stok_sebelum',
        'stok_sesudah',
    ];

    public function bonBarang(): BelongsTo
    {
        return $this->belongsTo(BonBarang::class, 'bon_barang_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
