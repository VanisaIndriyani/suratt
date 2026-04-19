<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\Notifikasi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $labels = [];
        $suratMasukDaily = [];
        $suratKeluarDaily = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $labels[] = $date;
            $suratMasukDaily[] = SuratMasuk::query()->whereDate('created_at', $date)->count();
            $suratKeluarDaily[] = SuratKeluar::query()->whereDate('created_at', $date)->count();
        }

        $stats = [
            'labels' => $labels,
            'surat_masuk_daily' => $suratMasukDaily,
            'surat_keluar_daily' => $suratKeluarDaily,
            'surat_masuk_total' => SuratMasuk::count(),
            'surat_keluar_total' => SuratKeluar::count(),
            'user_total' => User::count(),
            'role_counts' => User::query()
                ->selectRaw('role, COUNT(*) as total')
                ->groupBy('role')
                ->orderBy('role')
                ->pluck('total', 'role')
                ->toArray(),
            'my_disposisi_masuk' => $user->disposisiDiterima()->count(),
            'my_disposisi_dibuat' => $user->disposisiDibuat()->count(),
            'my_notifikasi_unread' => $user->notifikasis()->where('status', 'unread')->count(),
        ];

        if ($user->role === 'staf') {
            $stats['surat_masuk_status_counts'] = SuratMasuk::query()
                ->selectRaw("COALESCE(status, 'diproses') as status, COUNT(*) as total")
                ->groupBy('status')
                ->orderBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $stats['recent_surat_masuk'] = SuratMasuk::query()
                ->latest()
                ->limit(8)
                ->get(['id', 'nomor_surat', 'tanggal_surat', 'pengirim', 'perihal', 'status']);

            $stats['recent_surat_keluar'] = SuratKeluar::query()
                ->latest()
                ->limit(8)
                ->get(['id', 'nomor_surat', 'tanggal_surat', 'tujuan', 'perihal']);
        }

        if ($user->role === 'asmin') {
            $stats['surat_masuk_untuk_saya'] = SuratMasuk::query()
                ->whereHas('disposisis', function ($sub) use ($user) {
                    $sub->where('ke_user_id', $user->id);
                })
                ->count();

            $stats['surat_masuk_untuk_saya_status_counts'] = SuratMasuk::query()
                ->whereHas('disposisis', function ($sub) use ($user) {
                    $sub->where('ke_user_id', $user->id);
                })
                ->selectRaw("COALESCE(status, 'diproses') as status, COUNT(*) as total")
                ->groupBy('status')
                ->orderBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $stats['recent_disposisi_masuk'] = Disposisi::query()
                ->where('ke_user_id', $user->id)
                ->with(['suratMasuk', 'dariUser'])
                ->latest()
                ->limit(8)
                ->get();

            $stats['recent_surat_masuk_untuk_saya'] = SuratMasuk::query()
                ->whereHas('disposisis', function ($sub) use ($user) {
                    $sub->where('ke_user_id', $user->id);
                })
                ->latest()
                ->limit(8)
                ->get(['id', 'nomor_surat', 'tanggal_surat', 'pengirim', 'perihal', 'status']);
        }

        if ($user->role === 'kaskogartap') {
            $stats['surat_masuk_status_counts'] = SuratMasuk::query()
                ->selectRaw("COALESCE(status, 'diproses') as status, COUNT(*) as total")
                ->groupBy('status')
                ->orderBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $stats['surat_masuk_diproses_total'] = SuratMasuk::query()
                ->where('status', 'diproses')
                ->count();

            $stats['surat_masuk_selesai_total'] = SuratMasuk::query()
                ->where('status', 'selesai')
                ->count();

            $stats['recent_surat_masuk_diproses'] = SuratMasuk::query()
                ->where('status', 'diproses')
                ->latest()
                ->limit(8)
                ->get(['id', 'nomor_surat', 'tanggal_surat', 'pengirim', 'perihal', 'status']);

            $stats['recent_disposisi_keluar'] = Disposisi::query()
                ->where('dari_user_id', $user->id)
                ->with(['suratMasuk', 'keUser'])
                ->latest()
                ->limit(8)
                ->get();

            $stats['recent_notifikasi'] = Notifikasi::query()
                ->where('user_id', $user->id)
                ->latest()
                ->limit(6)
                ->get(['id', 'pesan', 'status', 'created_at']);
        }

        return match ($user->role) {
            'admin' => view('admin.dashboard', compact('stats')),
            'asmin' => view('asmin.dashboard', compact('stats')),
            'asops' => view('asops.dashboard', compact('stats')),
            'kasatker' => view('kasatker.dashboard', compact('stats')),
            'kaskogartap' => view('kaskogartap.dashboard', compact('stats')),
            default => view('staf.dashboard', compact('stats')),
        };
    }
}
