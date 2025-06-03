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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
    
            // Jenis layanan: array seperti ["Ketik", "Desain", "Cetak"]
            $table->json('services')->nullable();

            // Detail Ketik
            $table->string('doc_type')->nullable();
            $table->integer('page_count')->nullable();

            // Detail Desain
            $table->string('order_title')->nullable();
            $table->string('design_type')->nullable();
            $table->string('design_size')->nullable();

            // Detail Cetak
            $table->string('print_type')->nullable();
            $table->integer('print_quantity')->nullable();
            $table->string('print_material')->nullable();

            // Deadline dan Estimasi
            $table->dateTime('deadline')->nullable();
            $table->integer('estimate_time')->nullable();

            // Status dan Prioritas
            $table->string('status')->default('Menunggu');
            $table->string('priority')->default('normal');

            // Catatan khusus
            $table->text('special_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
