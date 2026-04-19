<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $tanggalFrom = trim((string) $request->query('tanggal_from', ''));
        $tanggalTo = trim((string) $request->query('tanggal_to', ''));

        $items = SuratKeluar::query()
            ->when($tanggalFrom !== '', function ($query) use ($tanggalFrom) {
                $query->whereDate('tanggal_surat', '>=', $tanggalFrom);
            })
            ->when($tanggalTo !== '', function ($query) use ($tanggalTo) {
                $query->whereDate('tanggal_surat', '<=', $tanggalTo);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nomor_surat', 'like', "%{$q}%")
                        ->orWhere('perihal', 'like', "%{$q}%")
                        ->orWhere('tujuan', 'like', "%{$q}%")
                        ->orWhere('jenis_surat', 'like', "%{$q}%")
                        ->orWhere('barcode', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('surat_keluar.index', [
            'items' => $items,
            'q' => $q,
            'tanggalFrom' => $tanggalFrom,
            'tanggalTo' => $tanggalTo,
        ]);
    }

    public function create()
    {
        return view('surat_keluar.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:255', 'unique:surat_keluar,nomor_surat'],
            'tanggal_surat' => ['required', 'date'],
            'tujuan' => ['required', 'string', 'max:255'],
            'jenis_surat' => ['nullable', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'file_surat' => ['nullable', 'file', 'max:10240'],
        ]);

        $path = null;
        if ($request->hasFile('file_surat')) {
            $path = $request->file('file_surat')->store('surat_keluar', 'public');
        }

        $barcode = (string) Str::uuid();

        $surat = SuratKeluar::create([
            'user_id' => $request->user()->id,
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'tujuan' => $validated['tujuan'],
            'jenis_surat' => $validated['jenis_surat'] ?? null,
            'perihal' => $validated['perihal'],
            'file_surat' => $path,
            'barcode' => $barcode,
        ]);

        return redirect()
            ->route('surat-keluar.show', $surat)
            ->with('success', 'Surat keluar berhasil ditambahkan.');
    }

    public function show(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['user']);

        return view('surat_keluar.show', [
            'item' => $suratKeluar,
        ]);
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        return view('surat_keluar.edit', [
            'item' => $suratKeluar,
        ]);
    }

    public function update(Request $request, SuratKeluar $suratKeluar)
    {
        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:255', 'unique:surat_keluar,nomor_surat,'.$suratKeluar->id],
            'tanggal_surat' => ['required', 'date'],
            'tujuan' => ['required', 'string', 'max:255'],
            'jenis_surat' => ['nullable', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'file_surat' => ['nullable', 'file', 'max:10240'],
        ]);

        if ($request->hasFile('file_surat')) {
            if ($suratKeluar->file_surat) {
                Storage::disk('public')->delete($suratKeluar->file_surat);
            }

            $suratKeluar->file_surat = $request->file('file_surat')->store('surat_keluar', 'public');
        }

        $suratKeluar->fill([
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'tujuan' => $validated['tujuan'],
            'jenis_surat' => $validated['jenis_surat'] ?? null,
            'perihal' => $validated['perihal'],
        ]);
        $suratKeluar->save();

        return redirect()
            ->route('surat-keluar.show', $suratKeluar)
            ->with('success', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->file_surat) {
            Storage::disk('public')->delete($suratKeluar->file_surat);
        }

        $suratKeluar->delete();

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil dihapus.');
    }

    public function downloadFile(SuratKeluar $suratKeluar)
    {
        abort_unless($suratKeluar->file_surat, 404);

        return Storage::disk('public')->download($suratKeluar->file_surat);
    }

    public function print(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['user']);

        return view('surat_keluar.print', [
            'item' => $suratKeluar,
        ]);
    }
}
