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
        Schema::create('pembelian_bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->string('no_faktur');
            $table->foreignid('kode_supplier')->constrained('supplier')->onDelete('cascade');
            $table->foreignid('kode_karyawan')->constrained('karyawans')->onDelete('cascade');
            $table->datetime('tgl_beli');
           //$table->integer('harga_satuan');
            $table->integer('total_beli');
            $table->enum('metode_pembayaran', ['tunai', 'kredit']);
            $table->datetime('jatuh_tempo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_bahan_bakus');
    }
};
