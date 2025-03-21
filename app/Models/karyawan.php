<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Karyawan extends Model
{
    /** @use HasFactory<\Database\Factories\KaryawanFactory> */
    use HasFactory;

    protected $table = 'karyawans';
    protected $guarded = [];

    public static function getKodeKaryawan()
    {
        // Ambil kode karyawan terakhir
        $sql = "SELECT IFNULL(MAX(kode_karyawan), 'KRY000') AS kode_karyawan FROM karyawans";
        $result = DB::select($sql);

        // Ambil hasil dari query
        $kd = $result[0]->kode_karyawan ?? 'KRY000';

        // Ambil 3 digit terakhir
        $noAwal = (int) substr($kd, -3);
        $noAkhir = $noAwal + 1;

        // Format jadi KRYxxx
        $kodeBaru = 'KRY' . str_pad($noAkhir, 3, '0', STR_PAD_LEFT);

        return $kodeBaru;
    }
}
