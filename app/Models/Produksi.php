<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $table = 'produksi'; // nama tabel sesuai db

    protected $primaryKey = 'id'; // primary key

    public $timestamps = true; // pakai timestamps

    protected $fillable = [
        'kode_produksi',
        'kode_karyawan',
        'kode_menu',
        'jumlah',
        'tgl_produksi',
        'porsi',
        'keterangan',
        'bahan_baku',
    ];

    protected $casts = [
        'tgl_produksi' => 'date',
        'bahan_baku' => 'array',
    ];

    // Relasi ke Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'kode_karyawan', 'id');
    }

    // Relasi ke Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'kode_menu', 'kode_menu');
    }

    
    // Event booting model
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($produksi) {
            $produksi->kode_produksi = static::generateKodeProduksi();
        });
    }

    // Method generate kode produksi otomatis
    protected static function generateKodeProduksi(): string
    {
        $last = static::orderBy('id', 'desc')->first();
        $lastNumber = $last ? intval(substr($last->kode_produksi, 3)) : 0;

        $newNumber = $lastNumber + 1;
        return 'PRD' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }



    // Mutator untuk bahan_baku decode dan nama bahan
    public function getBahanBakuAttribute($value)
    {
        $decoded = json_decode($value, true);
        if (!is_array($decoded)) return [];

        return collect($decoded)->map(function ($item) {
            $nama = \App\Models\BahanBaku::find($item['id'])?->nama_bahan_baku ?? 'Unknown';
            return [
                'nama' => $nama,
                'jumlah' => $item['jumlah']
            ];
        })->toArray();
    }
}
    