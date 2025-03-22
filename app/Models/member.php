<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class member extends Model
{
    use HasFactory;

    protected $table = 'member';

    protected $guarded = [];

public static function getidmember()
{
    // Query kode bahan baku terakhir
    $sql = "SELECT IFNULL(MAX(id_member), 'MM000') as id_member FROM member";
    $idmember = DB::select($sql);

    // Ambil hasil query
    foreach ($idmember as $kmm) {
        $km = $kmm->id_member;
    }

    // Mengambil substring tiga digit terakhir dari 'BB000'
    $noawal = substr($km, -3);
    $noakhir = (int)$noawal + 1; // Menambahkan 1

    // Format ulang menjadi 'BB001', 'BB002', dst.
    $noakhir = 'MM' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);
    
    return$noakhir;
}

}