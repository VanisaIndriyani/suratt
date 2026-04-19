@php($role = strtolower(trim((string) auth()->user()->role)))
@php($layout = $role === 'admin' ? 'layouts.admin' : 'layouts.'.$role)
@extends($layout)

@section('content')
    @php($statusLower = strtolower(trim((string) ($item->status ?? ''))))
    @php($statusBadge = match ($statusLower) {
        'selesai' => 'success',
        'diproses' => 'warning text-dark',
        'ditolak' => 'danger',
        default => 'secondary',
    })

    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Detail Surat Masuk</div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <div class="text-muted">{{ $item->nomor_surat }}</div>
                <span class="badge text-bg-{{ $statusBadge }} text-uppercase">{{ $item->status ?? '-' }}</span>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-secondary" title="Kembali" aria-label="Kembali">
                <i class="bi bi-arrow-left"></i>
            </a>
            <a href="{{ route('surat-masuk.print', $item) }}" class="btn btn-outline-secondary" title="Cetak" aria-label="Cetak">
                <i class="bi bi-printer"></i>
            </a>
            @if($item->file_surat || $item->file_gabungan)
                <a href="{{ route('surat-masuk.download', $item) }}" class="btn btn-outline-primary" title="{{ $item->file_gabungan ? 'Unduh Gabungan' : 'Unduh File' }}" aria-label="{{ $item->file_gabungan ? 'Unduh Gabungan' : 'Unduh File' }}">
                    <i class="bi bi-download"></i>
                </a>
            @endif
            @if($role === 'kaskogartap' && $item->file_surat && str_ends_with(strtolower($item->file_surat), '.pdf'))
                <form method="post" action="{{ route('surat-masuk.gabungan.regenerate', $item) }}" class="d-inline" data-confirm="Regenerate file gabungan untuk surat ini?">
                    @csrf
                  
                </form>
            @endif
            @if(in_array($role, ['staf', 'admin'], true))
                <a href="{{ route('surat-masuk.edit', $item) }}" class="btn btn-outline-secondary" title="Ubah" aria-label="Ubah">
                    <i class="bi bi-pencil"></i>
                </a>
                <form method="post" action="{{ route('surat-masuk.destroy', $item) }}" data-confirm="Hapus surat ini?" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger" type="submit" title="Hapus" aria-label="Hapus">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            @endif
            @if($role === 'kaskogartap')
                <a href="{{ route('surat-masuk.disposisi.create', $item) }}" class="btn btn-primary">
                    <i class="bi bi-send-check me-1"></i>
                    Buat Disposisi
                </a>
            @endif
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Informasi Surat</div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="text-muted" style="width: 220px;">Nomor Surat</th>
                                    <td class="fw-semibold">{{ $item->nomor_surat }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tanggal Surat</th>
                                    <td>{{ optional($item->tanggal_surat)->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tanggal Terima</th>
                                    <td>{{ optional($item->tanggal_terima)->format('Y-m-d') ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Pengirim</th>
                                    <td>{{ $item->pengirim }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Jenis Surat</th>
                                    <td>{{ $item->jenis_surat ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Perihal</th>
                                    <td>{{ $item->perihal }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Status</th>
                                    <td>
                                        <span class="badge text-bg-{{ $statusBadge }} text-uppercase">{{ $item->status ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Barcode</th>
                                    <td>
                                        <div class="fw-semibold text-break">{{ $item->barcode }}</div>
                                        <a class="link-primary text-decoration-none text-break" href="{{ route('barcode.show', $item->barcode) }}">
                                            <i class="bi bi-link-45deg me-1"></i>
                                            {{ route('barcode.show', $item->barcode) }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">File Surat</th>
                                    <td>
                                        @if($item->file_surat || $item->file_gabungan)
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('surat-masuk.download', $item) }}">
                                                <i class="bi bi-download me-1"></i>
                                                {{ $item->file_gabungan ? 'Unduh Gabungan' : 'Unduh File' }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="fw-semibold mb-2">QR untuk Scan</div>
                    <div class="d-flex align-items-center justify-content-center py-2">
                        <img
                            src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode(route('barcode.show', $item->barcode)) }}"
                            alt="QR"
                            width="220"
                            height="220"
                            class="img-fluid rounded-3 border p-2"
                            style="max-width: 220px;"
                        >
                    </div>
                    <div class="small text-muted text-center">Scan untuk membuka detail surat.</div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2">
                        <div>
                            <div class="fw-semibold">Disposisi</div>
                            <div class="text-muted small">Riwayat disposisi untuk surat ini ({{ $item->disposisis->count() }}).</div>
                        </div>
                        @if($role === 'kaskogartap')
                            <a href="{{ route('surat-masuk.disposisi.create', $item) }}" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle me-1"></i>
                                Tambah Disposisi
                            </a>
                        @endif
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 140px;">Tanggal</th>
                                    <th>Dari</th>
                                    <th>Ke</th>
                                    <th>Instruksi</th>
                                    <th style="width: 130px;">Status</th>
                                    <th class="text-end" style="width: 90px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($item->disposisis as $d)
                                    @php($canPrint = $role === 'admin' || auth()->id() === $d->dari_user_id || auth()->id() === $d->ke_user_id)
                                    <tr>
                                        <td>{{ optional($d->tanggal_disposisi)->format('Y-m-d') }}</td>
                                        <td>{{ $d->dariUser?->name ?? '-' }}</td>
                                        <td>{{ $d->keUser?->name ?? '-' }}</td>
                                        <td>{{ $d->instruksi }}</td>
                                        <td>
                                            @php($badge = match (strtolower((string) $d->status)) {
                                                'selesai' => 'success',
                                                'diproses' => 'warning text-dark',
                                                'ditolak' => 'danger',
                                                default => 'secondary',
                                            })
                                            <span class="badge text-bg-{{ $badge }}">{{ $d->status }}</span>
                                        </td>
                                        <td class="text-end">
                                            @if($canPrint)
                                                <a href="{{ route('disposisi.print', $d) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lembar Disposisi" aria-label="Lembar Disposisi">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">Belum ada disposisi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
