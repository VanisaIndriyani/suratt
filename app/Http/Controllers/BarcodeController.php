<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;

class BarcodeController extends Controller
{
    public function show(string $barcode)
    {
        $masuk = SuratMasuk::query()->where('barcode', $barcode)->first();
        if ($masuk) {
            return redirect()->route('surat-masuk.show', $masuk);
        }

        $keluar = SuratKeluar::query()->where('barcode', $barcode)->first();
        if ($keluar) {
            return redirect()->route('surat-keluar.show', $keluar);
        }

        abort(404);
    }
}
