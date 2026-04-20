<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratMasukController extends Controller
{
    private function canView(User $user, SuratMasuk $suratMasuk): bool
    {
        $role = strtolower(trim((string) $user->role));

        if (in_array($role, ['admin', 'staf', 'kaskogartap'], true)) {
            return true;
        }

        if (in_array($role, ['asmin', 'asops', 'kasatker'], true)) {
            return $suratMasuk->disposisis()->where('ke_user_id', $user->id)->exists();
        }

        return false;
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $tanggalFrom = trim((string) $request->query('tanggal_from', ''));
        $tanggalTo = trim((string) $request->query('tanggal_to', ''));
        $role = strtolower(trim((string) $request->user()->role));

        $items = SuratMasuk::query()
            ->when(in_array($role, ['asmin', 'asops', 'kasatker'], true), function ($query) use ($request) {
                $query->whereHas('disposisis', function ($sub) use ($request) {
                    $sub->where('ke_user_id', $request->user()->id);
                });
            })
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
                        ->orWhere('pengirim', 'like', "%{$q}%")
                        ->orWhere('jenis_surat', 'like', "%{$q}%")
                        ->orWhere('barcode', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('surat_masuk.index', [
            'items' => $items,
            'q' => $q,
            'tanggalFrom' => $tanggalFrom,
            'tanggalTo' => $tanggalTo,
        ]);
    }

    public function create()
    {
        return view('surat_masuk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:255', 'unique:surat_masuk,nomor_surat'],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_terima' => ['nullable', 'date'],
            'pengirim' => ['required', 'string', 'max:255'],
            'jenis_surat' => ['nullable', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'file_surat' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ], [
            'file_surat.mimes' => 'File surat harus berformat PDF.',
            'file_surat.max' => 'Ukuran file surat maksimal 10 MB.',
        ]);

        $path = null;
        if ($request->hasFile('file_surat')) {
            $path = $request->file('file_surat')->store('surat_masuk', 'public');
        }

        $barcode = (string) Str::uuid();

        $surat = SuratMasuk::create([
            'user_id' => $request->user()->id,
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'tanggal_terima' => $validated['tanggal_terima'] ?? null,
            'pengirim' => $validated['pengirim'],
            'jenis_surat' => $validated['jenis_surat'] ?? null,
            'perihal' => $validated['perihal'],
            'file_surat' => $path,
            'barcode' => $barcode,
            'status' => 'diproses',
        ]);

        return redirect()
            ->route('surat-masuk.show', $surat)
            ->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    public function show(SuratMasuk $suratMasuk)
    {
        abort_unless($this->canView(request()->user(), $suratMasuk), 403);

        $suratMasuk->load(['user', 'disposisis.keUser', 'disposisis.dariUser']);

        return view('surat_masuk.show', [
            'item' => $suratMasuk,
        ]);
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        return view('surat_masuk.edit', [
            'item' => $suratMasuk,
        ]);
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:255', 'unique:surat_masuk,nomor_surat,'.$suratMasuk->id],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_terima' => ['nullable', 'date'],
            'pengirim' => ['required', 'string', 'max:255'],
            'jenis_surat' => ['nullable', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'file_surat' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ], [
            'file_surat.mimes' => 'File surat harus berformat PDF.',
            'file_surat.max' => 'Ukuran file surat maksimal 10 MB.',
        ]);

        if ($request->hasFile('file_surat')) {
            if ($suratMasuk->file_surat) {
                Storage::disk('public')->delete($suratMasuk->file_surat);
            }

            $suratMasuk->file_surat = $request->file('file_surat')->store('surat_masuk', 'public');
        }

        $suratMasuk->fill([
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'tanggal_terima' => $validated['tanggal_terima'] ?? null,
            'pengirim' => $validated['pengirim'],
            'jenis_surat' => $validated['jenis_surat'] ?? null,
            'perihal' => $validated['perihal'],
        ]);
        $suratMasuk->save();

        return redirect()
            ->route('surat-masuk.show', $suratMasuk)
            ->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        if ($suratMasuk->file_surat) {
            Storage::disk('public')->delete($suratMasuk->file_surat);
        }

        $suratMasuk->delete();

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil dihapus.');
    }

    public function downloadFile(SuratMasuk $suratMasuk)
    {
        abort_unless($this->canView(request()->user(), $suratMasuk), 403);
        $path = $suratMasuk->file_gabungan ?: $suratMasuk->file_surat;
        abort_unless($path, 404);

        return Storage::disk('public')->download($path);
    }

    public function print(SuratMasuk $suratMasuk)
    {
        abort_unless($this->canView(request()->user(), $suratMasuk), 403);
        $suratMasuk->load(['user', 'disposisis.keUser', 'disposisis.dariUser']);

        return view('surat_masuk.print', [
            'item' => $suratMasuk,
        ]);
    }
}
