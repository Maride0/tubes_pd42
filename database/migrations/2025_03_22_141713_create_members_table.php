<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member', function (Blueprint $table) {
            $table->id(); 
            $table->string('id_member')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); //jika parent di hapus, maka anak akan ikut terhapus
            $table->string('nama');
            $table->string('alamat');
            $table->string('no_telp');
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('member');
    }
};