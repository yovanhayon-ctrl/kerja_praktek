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
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('whatsapp');
            $table->dateTime('waktu_reservasi'); // Menyimpan tanggal sekaligus jam datang
            $table->integer('nomor_meja');
            $table->text('catatan')->nullable();
            $table->string('status')->default('PENDING'); // Status awal saat pelanggan melakukan booking
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
