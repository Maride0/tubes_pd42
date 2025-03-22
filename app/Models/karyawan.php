<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';
    protected $guarded = [];

    /**
     * Generate kode karyawan otomatis dengan format KRYxxx.
     *
     * @return string
     */
    public static function getKodeKaryawan(): string
    {
        // Ambil kode karyawan terakhir dari database
        $result = DB::select("SELECT IFNULL(MAX(kode_karyawan), 'KRY000') AS kode_karyawan FROM karyawans");

        // Ambil hasil kode terakhir atau default 'KRY000'
        $lastCode = $result[0]->kode_karyawan ?? 'KRY000';

        // Ambil 3 digit angka terakhir dan tambahkan 1
        $number = (int) substr($lastCode, -3) + 1;

        // Format jadi KRYxxx
        return 'KRY' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
