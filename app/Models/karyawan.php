<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    // Kalau field-nya beda dari 'id', tambahkan ini (optional)
    // protected $primaryKey = 'kode_karyawan';
    // public $incrementing = false;

    /**
     * Generate kode karyawan otomatis
     */
    public static function getKodeKaryawan()
    {
        // Ambil record terakhir berdasarkan kode_karyawan
        $lastKode = self::orderBy('kode_karyawan', 'desc')->first()?->kode_karyawan;

        if (!$lastKode) {
            return 'KR001';
        }

        // Ambil angka dari kode terakhir, misal: KR005 → 5
        $lastNumber = (int) substr($lastKode, 2);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return 'KR' . $newNumber;
    }
}
