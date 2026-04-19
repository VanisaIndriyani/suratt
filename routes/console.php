<?php

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('surat:purge-old {--years=10}', function () {
    $years = (int) $this->option('years');
    if ($years < 1) {
        $years = 10;
    }

    $cutoff = now()->subYears($years)->toDateString();
    $deletedMasuk = 0;
    $deletedKeluar = 0;

    SuratMasuk::query()
        ->whereDate('tanggal_surat', '<', $cutoff)
        ->chunkById(200, function ($items) use (&$deletedMasuk) {
            foreach ($items as $item) {
                if ($item->file_surat) {
                    Storage::disk('public')->delete($item->file_surat);
                }
                $item->delete();
                $deletedMasuk++;
            }
        });

    SuratKeluar::query()
        ->whereDate('tanggal_surat', '<', $cutoff)
        ->chunkById(200, function ($items) use (&$deletedKeluar) {
            foreach ($items as $item) {
                if ($item->file_surat) {
                    Storage::disk('public')->delete($item->file_surat);
                }
                $item->delete();
                $deletedKeluar++;
            }
        });

    $this->info("Purge selesai. Surat Masuk terhapus: {$deletedMasuk}, Surat Keluar terhapus: {$deletedKeluar}. Cutoff: {$cutoff}");
})->purpose('Hapus otomatis surat yang lebih tua dari N tahun');

Schedule::command('surat:purge-old')->dailyAt('01:00');
