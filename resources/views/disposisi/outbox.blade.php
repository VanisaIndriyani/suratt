@php($role = strtolower(trim((string) auth()->user()->role)))
@php($layout = $role === 'admin' ? 'layouts.admin' : 'layouts.'.$role)
@extends($layout)

@section('content')
    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Disposisi Keluar</div>
            <div class="text-muted">Daftar disposisi yang kamu kirim.</div>
        </div>
        <a href="{{ route('disposisi.inbox') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-right me-1"></i>
            Disposisi Masuk
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="get" class="row g-2 align-items-center">
                <div class="col-12 col-lg-5">
                    <input name="q" value="{{ $q ?? '' }}" placeholder="Cari nomor/perihal/pengirim/ke/status/instruksi..." class="form-control">
                </div>
                <div class="col-12 col-lg-5 d-flex gap-2">
                    <input type="date" name="tanggal_from" value="{{ $tanggalFrom ?? '' }}" class="form-control" title="Tanggal dari">
                    <input type="date" name="tanggal_to" value="{{ $tanggalTo ?? '' }}" class="form-control" title="Tanggal sampai">
                </div>
                <div class="col-12 col-lg-2 d-flex gap-2">
                    <button class="btn btn-primary" type="submit" title="Cari" aria-label="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('disposisi.outbox') }}" class="btn btn-outline-secondary" title="Reset" aria-label="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            @php($no = $items->firstItem() ?? 1)
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">No</th>
                        <th style="width: 120px;">Tanggal</th>
                        <th style="width: 180px;">Ke</th>
                        <th style="width: 190px;">Surat</th>
                        <th>Instruksi</th>
                        <th style="width: 120px;">Status</th>
                        <th class="text-end" style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $no + $loop->index }}</td>
                            <td>{{ optional($item->tanggal_disposisi)->format('Y-m-d') }}</td>
                            <td>{{ $item->keUser?->name ?? '-' }}</td>
                            <td>
                                <a class="link-primary text-decoration-none fw-semibold" href="{{ route('surat-masuk.show', $item->suratMasuk) }}">
                                    {{ $item->suratMasuk?->nomor_surat }}
                                </a>
                                <div class="text-muted small text-truncate" style="max-width: 180px;">{{ $item->suratMasuk?->perihal ?? '-' }}</div>
                            </td>
                            <td>{{ $item->instruksi }}</td>
                            <td>
                                @php($badge = match (strtolower((string) $item->status)) {
                                    'selesai' => 'success',
                                    'diproses' => 'warning text-dark',
                                    'baru' => 'primary',
                                    'ditolak' => 'danger',
                                    default => 'secondary',
                                })
                                <span class="badge text-bg-{{ $badge }}">{{ $item->status }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('disposisi.print', $item) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lembar Disposisi" aria-label="Lembar Disposisi">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Belum ada disposisi keluar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">
            {{ $items->links() }}
        </div>
    </div>
@endsection
