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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perizinan');
            $table->string('jenis_perizinan');
            $table->string('instansi_penerbit');
            $table->string('nomor_izin')->unique();
            $table->date('tanggal_terbit');
            $table->date('tanggal_berakhir');
            $table->integer('masa_berlaku_hari')->default(0);
            $table->string('penanggung_jawab');
            $table->string('email_notifikasi');
            $table->enum('status', ['AKTIF', 'AKAN HABIS', 'KADALUARSA'])->default('AKTIF');
            $table->timestamps();

            $table->index('status');
            $table->index('tanggal_berakhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
