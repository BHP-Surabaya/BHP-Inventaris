<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BonBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_bon',
        'tanggal',
        'nama_pemohon',
        'seksi_pemohon',
        'keperluan',
        'user_id',
        'total_item',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BonBarangItem::class, 'bon_barang_id');
    }
}
