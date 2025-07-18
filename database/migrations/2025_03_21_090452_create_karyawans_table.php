<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
                $table->id();
                $table->string('kode_karyawan');
                $table->string('nama');
                $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']); // Jenis kelamin
                $table->string('no_telepon');
                $table->text('alamat');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
