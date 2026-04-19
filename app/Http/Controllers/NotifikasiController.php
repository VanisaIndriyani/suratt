<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $items = Notifikasi::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('notifikasi.index', [
            'items' => $items,
        ]);
    }

    public function markRead(Request $request, Notifikasi $notifikasi)
    {
        abort_unless($notifikasi->user_id === $request->user()->id, 403);

        $notifikasi->update([
            'status' => 'read',
        ]);

        return back()->with('success', 'Notifikasi ditandai dibaca.');
    }
}
