<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// untuk tambahan db
use Illuminate\Support\Facades\DB;

class Penjualan extends Model
{
    protected $table = 'penjualan'; // Nama tabel eksplisit

    protected $guarded = [];

    public static function getKodePenjualan()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(no_penjualan), 'PNJ-0000000') as no_penjualan 
                FROM penjualan ";
        $kodepnj = DB::select($sql);

        // cacah hasilnya
        foreach ($kodepnj as $kdpnj) {
            $kd = $kdpnj->no_penjualan;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-7);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'PNJ-'.str_pad($noakhir,7,"0",STR_PAD_LEFT); //menyambung dengan string P-00001
        return $noakhir;

    }

    // relasi ke tabel pembeli
    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member');
    }

    // relasi ke tabel penjualan barang
    public function penjualanBarang()
    {
        return $this->hasMany(PenjualanBarang::class, 'penjualan_id');
    }

}
