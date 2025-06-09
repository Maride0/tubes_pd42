<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian';
    protected $fillable = [
        'pembelian_bahan_baku_id',
        'kode_bahan_baku',
        'quantity',
        'harga_satuan',
    ];

    public function pembelian()
    {
        return $this->belongsTo(PembelianBahanBaku::class, 'pembelian_bahan_baku_id');
    }

    public function bahanbaku()
    {
        return $this->belongsTo(BahanBaku::class, 'kode_bahan_baku');
    }
}
