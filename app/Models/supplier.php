<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    protected $guarded = [];

public static function getKodeSupplier()
{
    // Query kode bahan baku terakhir
    $sql = "SELECT IFNULL(MAX(kode_supplier), 'SP000') as kode_supplier FROM supplier";
    $kodesupplier = DB::select($sql);

    // Ambil hasil query
    foreach ($kodesupplier as $kdsp) {
        $ks = $kdsp->kode_supplier;
    }

    // Mengambil substring tiga digit terakhir dari 'BB000'
    $noawal = substr($ks, -3);
    $noakhir = (int)$noawal + 1; // Menambahkan 1

    // Format ulang menjadi 'BB001', 'BB002', dst.
    $noakhir = 'SP' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);
    
    return $noakhir;
}

}
