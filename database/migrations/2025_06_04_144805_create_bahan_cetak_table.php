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
        Schema::create('bahan_cetak', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan');
            $table->string('jenis_bahan');
            $table->string('gramatur')->nullable();
            $table->string('ukuran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_cetak');
    }
};
