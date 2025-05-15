<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PembelianBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'pembelian_bahan_baku';
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'kode_supplier');
    }

    public function bahanbaku()
    {
        return $this->belongsTo(BahanBaku::class, 'kode_bahan_baku');
    }

    public function karyawans()
    {
        return $this->belongsTo(Karyawan::class, 'kode_karyawan');
    }

    public function detailPembelian()
{
    return $this->hasMany(DetailPembelian::class, 'pembelian_bahan_baku_id');
}

}

