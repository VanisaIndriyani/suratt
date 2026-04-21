<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\Notifikasi;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Services\WhatsappService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Throwable;

class DisposisiController extends Controller
{
    private const DISPOSISI_KASKOGARTAP_OPTIONS = [
        'ACC',
        'ACARAKAN',
        'BALAS',
        'BANTU',
        'DUKUNG',
        'IKUTI PERKEMBANGAN',
        'HADIR',
        'TIDAK HADIR',
        'KOORDINASIKAN',
        'LAPORKAN',
        'PELAJARI & TELITI',
        'PEDOMANI',
        'SEBAGAI BAHAN',
        'SIAPKAN',
        'TINDAK LANJUTI',
        'TANGGAPAN & SARAN',
        'TERUSKAN KE SATWAH',
        'WAKILI',
        'ARSIP',
        'CATAT',
        'INGATKAN',
        'MONITOR',
        'UDL',
        'UDK',
    ];

    private function generatePdfAndMerge(Disposisi $disposisi, SuratMasuk $suratMasuk): array
    {
        $pdfDisposisiPath = null;
        $pdfGabunganPath = null;
        $mergeInfo = '';

        try {
            $disposisi->loadMissing(['suratMasuk', 'dariUser', 'keUser']);

            $pdfDisposisiPath = "disposisi/disposisi-{$disposisi->id}.pdf";
            $pdfData = Pdf::setOption(['isRemoteEnabled' => true])
                ->loadView('disposisi.pdf', [
                    'item' => $disposisi,
                ])
                ->setPaper('a4')
                ->output();

            Storage::disk('public')->put($pdfDisposisiPath, $pdfData);
            $disposisi->update(['file_pdf' => $pdfDisposisiPath]);

            $suratPath = (string) ($suratMasuk->file_surat ?? '');
            $isSuratPdf = $suratPath !== '' && str_ends_with(strtolower($suratPath), '.pdf') && Storage::disk('public')->exists($suratPath);

            if ($isSuratPdf) {
                $fpdi = new Fpdi;
                foreach ([Storage::disk('public')->path($pdfDisposisiPath), Storage::disk('public')->path($suratPath)] as $filePath) {
                    $pageCount = $fpdi->setSourceFile($filePath);
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        $tplId = $fpdi->importPage($pageNo);
                        $size = $fpdi->getTemplateSize($tplId);
                        $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
                        $fpdi->useTemplate($tplId);
                    }
                }

                $pdfGabunganPath = "surat_masuk/gabungan/{$suratMasuk->barcode}-gabungan.pdf";
                Storage::disk('public')->put($pdfGabunganPath, $fpdi->Output('S'));
                $suratMasuk->update(['file_gabungan' => $pdfGabunganPath]);
                $mergeInfo = ' File gabungan berhasil dibuat.';
            } elseif ($suratPath !== '') {
                $mergeInfo = ' File surat bukan PDF, jadi file gabungan tidak dibuat.';
            }
        } catch (Throwable) {
            $pdfDisposisiPath = null;
            $pdfGabunganPath = null;
            if ($suratMasuk->file_surat) {
                $mergeInfo = ' File gabungan gagal dibuat (pastikan file surat PDF dan tidak dipassword).';
            }
        }

        return [$pdfDisposisiPath, $pdfGabunganPath, $mergeInfo];
    }

    private function canAccessDisposisi(User $user, Disposisi $disposisi): bool
    {
        $role = strtolower(trim((string) $user->role));

        if ($role === 'admin') {
            return true;
        }

        if ($role === 'kaskogartap') {
            return true;
        }

        return $disposisi->dari_user_id === $user->id || $disposisi->ke_user_id === $user->id;
    }

    private function allowedRecipientRoles(string $senderRole): array
    {
        $senderRole = strtolower(trim($senderRole));

        return match ($senderRole) {
            'kaskogartap' => ['kasatker', 'asmin', 'asops'],
            default => [],
        };
    }

    public function inbox(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $tanggalFrom = trim((string) $request->query('tanggal_from', ''));
        $tanggalTo = trim((string) $request->query('tanggal_to', ''));

        $items = Disposisi::query()
            ->where('ke_user_id', $request->user()->id)
            ->with(['suratMasuk', 'dariUser'])
            ->when($tanggalFrom !== '', function ($query) use ($tanggalFrom) {
                $query->whereDate('tanggal_disposisi', '>=', $tanggalFrom);
            })
            ->when($tanggalTo !== '', function ($query) use ($tanggalTo) {
                $query->whereDate('tanggal_disposisi', '<=', $tanggalTo);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('instruksi', 'like', "%{$q}%")
                        ->orWhere('status', 'like', "%{$q}%")
                        ->orWhereHas('suratMasuk', function ($s) use ($q) {
                            $s->where('nomor_surat', 'like', "%{$q}%")
                                ->orWhere('perihal', 'like', "%{$q}%")
                                ->orWhere('pengirim', 'like', "%{$q}%");
                        })
                        ->orWhereHas('dariUser', function ($u) use ($q) {
                            $u->where('name', 'like', "%{$q}%")
                                ->orWhere('username', 'like', "%{$q}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('disposisi.inbox', [
            'items' => $items,
            'q' => $q,
            'tanggalFrom' => $tanggalFrom,
            'tanggalTo' => $tanggalTo,
        ]);
    }

    public function outbox(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $tanggalFrom = trim((string) $request->query('tanggal_from', ''));
        $tanggalTo = trim((string) $request->query('tanggal_to', ''));

        $items = Disposisi::query()
            ->where('dari_user_id', $request->user()->id)
            ->with(['suratMasuk', 'keUser'])
            ->when($tanggalFrom !== '', function ($query) use ($tanggalFrom) {
                $query->whereDate('tanggal_disposisi', '>=', $tanggalFrom);
            })
            ->when($tanggalTo !== '', function ($query) use ($tanggalTo) {
                $query->whereDate('tanggal_disposisi', '<=', $tanggalTo);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('instruksi', 'like', "%{$q}%")
                        ->orWhere('status', 'like', "%{$q}%")
                        ->orWhereHas('suratMasuk', function ($s) use ($q) {
                            $s->where('nomor_surat', 'like', "%{$q}%")
                                ->orWhere('perihal', 'like', "%{$q}%")
                                ->orWhere('pengirim', 'like', "%{$q}%");
                        })
                        ->orWhereHas('keUser', function ($u) use ($q) {
                            $u->where('name', 'like', "%{$q}%")
                                ->orWhere('username', 'like', "%{$q}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('disposisi.outbox', [
            'items' => $items,
            'q' => $q,
            'tanggalFrom' => $tanggalFrom,
            'tanggalTo' => $tanggalTo,
        ]);
    }

    public function create(SuratMasuk $suratMasuk)
    {
        $allowedRoles = $this->allowedRecipientRoles(auth()->user()->role);
        abort_if($allowedRoles === [], 403);

        if ($suratMasuk->disposisis()->exists()) {
            return redirect()
                ->route('surat-masuk.show', $suratMasuk)
                ->with('error', 'Surat ini sudah memiliki disposisi dan tidak dapat didisposisi ulang.');
        }

        $users = User::query()
            ->whereIn('role', $allowedRoles)
            ->orderBy('name')
            ->get(['id', 'name', 'role', 'no_hp']);

        return view('disposisi.create', [
            'surat' => $suratMasuk,
            'users' => $users,
            'disposisiKaskogartapOptions' => self::DISPOSISI_KASKOGARTAP_OPTIONS,
        ]);
    }

    public function store(Request $request, SuratMasuk $suratMasuk, WhatsappService $whatsappService)
    {
        $allowedRoles = $this->allowedRecipientRoles($request->user()->role);
        abort_if($allowedRoles === [], 403);

        if ($suratMasuk->disposisis()->exists()) {
            return redirect()
                ->route('surat-masuk.show', $suratMasuk)
                ->with('error', 'Surat ini sudah memiliki disposisi dan tidak dapat didisposisi ulang.');
        }

        $validated = $request->validate([
            'ke_user_id' => ['required', 'integer', 'exists:users,id'],
            'disposisi_kaskogartap' => ['required', 'string', 'max:255', 'in:'.implode(',', self::DISPOSISI_KASKOGARTAP_OPTIONS)],
            'instruksi' => ['nullable', 'string'],
        ]);

        $penerima = User::query()
            ->whereIn('role', $allowedRoles)
            ->findOrFail($validated['ke_user_id']);

        $disposisi = Disposisi::create([
            'surat_masuk_id' => $suratMasuk->id,
            'dari_user_id' => $request->user()->id,
            'ke_user_id' => $penerima->id,
            'disposisi_kaskogartap' => $validated['disposisi_kaskogartap'],
            'instruksi' => $validated['instruksi'] ?? '',
            'tanggal_disposisi' => now()->toDateString(),
            'status' => 'selesai',
        ]);

        $suratMasuk->update([
            'status' => 'selesai',
        ]);

        [, , $mergeInfo] = $this->generatePdfAndMerge($disposisi, $suratMasuk);

        $linkDisposisi = route('disposisi.print', $disposisi);
        $linkSurat = route('surat-masuk.show', $suratMasuk);
        $linkUnduhFile = ($suratMasuk->file_surat || $suratMasuk->file_gabungan) ? route('surat-masuk.download', $suratMasuk) : null;
        $labelUnduh = $suratMasuk->file_gabungan ? 'File gabungan' : 'File surat';

        Notifikasi::create([
            'user_id' => $penerima->id,
            'pesan' => "Disposisi baru untuk surat masuk {$suratMasuk->nomor_surat} ({$suratMasuk->perihal}). Link disposisi: {$linkDisposisi}".($linkUnduhFile ? " | {$labelUnduh}: {$linkUnduhFile}" : ''),
            'status' => 'unread',
        ]);

        if ($penerima->no_hp) {
            $message = "Disposisi baru: Surat {$suratMasuk->nomor_surat}\nPerihal: {$suratMasuk->perihal}\nInstruksi: {$disposisi->instruksi}\nLink disposisi: {$linkDisposisi}\nLink detail surat: {$linkSurat}";
            if ($linkUnduhFile) {
                $message .= "\nLink {$labelUnduh}: {$linkUnduhFile}";
            }

            $whatsappService->send(
                $penerima->no_hp,
                $message
            );
        }

        return redirect()
            ->route('surat-masuk.show', $suratMasuk)
            ->with('success', 'Disposisi berhasil dibuat dan notifikasi dikirim.'.$mergeInfo);
    }

    public function regenerateGabungan(SuratMasuk $suratMasuk)
    {
        abort_if(strtolower((string) request()->user()->role) !== 'kaskogartap', 403);

        $disposisi = Disposisi::query()
            ->where('surat_masuk_id', $suratMasuk->id)
            ->latest()
            ->first();

        if (! $disposisi) {
            return back()->with('error', 'Belum ada disposisi untuk surat ini.');
        }

        [, , $mergeInfo] = $this->generatePdfAndMerge($disposisi, $suratMasuk);

        return back()->with('success', 'Regenerate file gabungan selesai.'.$mergeInfo);
    }

    public function printSheet(Disposisi $disposisi)
    {
        $disposisi->load(['suratMasuk', 'dariUser', 'keUser']);
        abort_unless($this->canAccessDisposisi(request()->user(), $disposisi), 403);

        return view('disposisi.print', [
            'item' => $disposisi,
        ]);
    }
}
