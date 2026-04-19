<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat');
            $table->string('tujuan');
            $table->string('jenis_surat')->nullable();
            $table->string('perihal');
            $table->string('file_surat')->nullable();
            $table->string('barcode')->unique();
            $table->timestamps();

            $table->index(['tanggal_surat', 'tujuan']);
            $table->index(['nomor_surat', 'perihal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
