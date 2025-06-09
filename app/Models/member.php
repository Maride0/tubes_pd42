<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Member extends Model
{
    use HasFactory;

    protected $table = 'member';

    protected $fillable = [
        'user_id',
        'id_member',
        'nama',
        'alamat',
        'no_telp',
    ];

    public static function getidmember()
    {
        $sql = "SELECT IFNULL(MAX(id_member), 'MM000') as id_member FROM member";
        $idmember = DB::select($sql);

        foreach ($idmember as $kmm) {
            $km = $kmm->id_member;
        }

        $noawal = substr($km, -3);
        $noakhir = (int)$noawal + 1;

        return 'MM' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'member_id');
    }
}