<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member', function (Blueprint $table) {
            $table->increments('id'); // AUTO_INCREMENT dan PRIMARY KEY
            $table->string('id_member')->unique(); // Jadikan unik tapi bukan primary
            $table->string('nama');
            $table->string('no_telp');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member');
    }
};