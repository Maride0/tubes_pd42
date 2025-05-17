<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('produksi', function (Blueprint $table) {
           $table->id();
            $table->string('kode_menu');
            $table->foreignId('kode_karyawan')->constrained('karyawans')->onDelete('cascade');
            $table->string('kode_produksi');
            $table->integer('jumlah');
            $table->integer('porsi')->nullable();
            $table->date('tgl_produksi');
            $table->text('keterangan')->nullable();
            $table->json('bahan_baku')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void {
        Schema::dropIfExists('produksi');
    }
};
