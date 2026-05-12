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
    Schema::create('pesanans', function (Blueprint $table) {
        $table->id();
        $table->string('id_pesanan')->unique(); // Contoh: #RK-9210
        $table->string('nama_pelanggan');
        $table->string('nomor_meja')->nullable();
        $table->text('detail_menu'); // Disimpan dalam format JSON atau teks
        $table->decimal('total_harga', 10, 2);
        $table->enum('status', ['PENDING', 'DIPROSES', 'SELESAI', 'DIBATALKAN'])->default('PENDING');
        $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
