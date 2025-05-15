<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanBarang extends Model
{
    use HasFactory;

    protected $table = 'penjualan_barang';
    protected $fillable = ['penjualan_id', 'kode_menu', 'harga_beli', 'harga_jual', 'jumlah', 'tanggal'];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'kode_menu', 'id');
    }
}