<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->constrained('surat_masuk')->cascadeOnDelete();
            $table->foreignId('dari_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ke_user_id')->constrained('users')->cascadeOnDelete();
            $table->text('instruksi');
            $table->date('tanggal_disposisi');
            $table->string('status')->default('baru');
            $table->timestamps();

            $table->index(['ke_user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposisi');
    }
};
