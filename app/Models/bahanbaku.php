<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class bahanbaku extends Model
{
    use HasFactory;

    protected $table = 'bahanbaku';

    protected $guarded = [];

public static function getKodeBahanBaku()
{
    // Query kode bahan baku terakhir
    $sql = "SELECT IFNULL(MAX(kode_bahan_baku), 'BB000') as kode_bahan_baku FROM bahanbaku";
    $kodebahanbaku = DB::select($sql);

    // Ambil hasil query
    foreach ($kodebahanbaku as $kdbb) {
        $kd = $kdbb->kode_bahan_baku;
    }

    // Mengambil substring tiga digit terakhir dari 'BB000'
    $noawal = substr($kd, -3);
    $noakhir = (int)$noawal + 1; // Menambahkan 1

    // Format ulang menjadi 'BB001', 'BB002', dst.
    $noakhir = 'BB' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);
    
    return $noakhir;
}

// Dengan mutator ini, setiap kali data harga_Menu dikirim ke database, koma akan otomatis dihapus.
public function setHargaSatuanAttribute($value)
{
    // Hapus koma (,) dari nilai sebelum menyimpannya ke database
    $this->attributes['harga_satuan'] = str_replace('.', '', $value);
}

 public function detailPembelian()
{
    return $this->hasMany(DetailPembelian::class, 'kode_bahan_baku');
}
}
