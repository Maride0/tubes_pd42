<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coa extends Model
{
    use HasFactory;

    protected $table = 'coas';

    protected $guarded = [];
}
