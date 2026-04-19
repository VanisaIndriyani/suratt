<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->string('status')->default('diproses')->after('barcode');
            $table->index(['status', 'tanggal_surat']);
        });
    }

    public function down(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropIndex(['status', 'tanggal_surat']);
            $table->dropColumn('status');
        });
    }
};
