<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coa extends Model
{
    use HasFactory;

    protected $table = 'coas';
     //mendefinisikan nama tabelnya itu berubah jadi coa bukan coas lagi

    protected $guarded = [];
    //maksud nya adalah semua kolom yang ada di tabel ini bisa di manipulasi
    /** @use HasFactory<\Database\Factories\CoaFactory> */

    public function setSaldoAwalAttribute($value)
    {
        // Hapus koma (,) dari nilai sebelum menyimpannya ke database
        $this->attributes['saldo_awal'] = str_replace('.', '', $value);
    }
}