<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bahanbaku', function (Blueprint $table) {
            $table->id(); // Primary key otomatis (id bigint auto-increment)
            $table->string('kode_bahan_baku', 5)->unique(); // ID custom
            $table->string('nama_bahan_baku', 50);
            $table->enum('kategori_bahan_baku', ['Bumbu', 'Minuman', 'Daging', 'Sayuran','Lainnya']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahanbaku');
    }
};
