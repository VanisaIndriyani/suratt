@extends('layouts.kaskogartap')

@section('content')
    @php($statusCounts = $stats['surat_masuk_status_counts'] ?? [])
    @php($suratMasukDiproses = (int) ($stats['surat_masuk_diproses_total'] ?? ($statusCounts['diproses'] ?? 0)))
    @php($suratMasukSelesai = (int) ($stats['surat_masuk_selesai_total'] ?? ($statusCounts['selesai'] ?? 0)))

    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Dashboard</div>
            <div class="text-muted">Ringkasan surat masuk, prioritas disposisi, dan aktivitas terbaru.</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('surat-masuk.index') }}" class="btn btn-primary">
                <i class="bi bi-inbox me-1"></i>
                Surat Masuk
            </a>
            <a href="{{ route('disposisi.outbox') }}" class="btn btn-outline-primary">
                <i class="bi bi-send-check me-1"></i>
                Disposisi Keluar
            </a>
            <a href="{{ route('notifikasi.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-bell me-1"></i>
                Notifikasi
            </a>
        </div>
    </div>

    @php($todayIncoming = $stats['surat_masuk_daily'][count($stats['surat_masuk_daily']) - 1] ?? 0)

    <div class="row g-3">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">Surat Masuk</div>
                            <div class="display-6 fw-semibold">{{ $stats['surat_masuk_total'] }}</div>
                            <div class="text-muted small">Total</div>
                        </div>
                        <div class="rounded-3 bg-primary-subtle text-primary p-2">
                            <i class="bi bi-inbox fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">Disposisi Dikirim</div>
                            <div class="display-6 fw-semibold">{{ $stats['my_disposisi_dibuat'] ?? 0 }}</div>
                            <div class="text-muted small">Total disposisi</div>
                        </div>
                        <div class="rounded-3 bg-success-subtle text-success p-2">
                            <i class="bi bi-send-check fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">Perlu Disposisi</div>
                            <div class="display-6 fw-semibold">{{ $suratMasukDiproses }}</div>
                            <div class="text-muted small">Status diproses</div>
                        </div>
                        <div class="rounded-3 bg-warning-subtle text-warning p-2">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">Notifikasi</div>
                            <div class="display-6 fw-semibold">{{ $stats['my_notifikasi_unread'] ?? 0 }}</div>
                            <div class="text-muted small">Belum dibaca</div>
                        </div>
                        <div class="rounded-3 bg-info-subtle text-info p-2">
                            <i class="bi bi-bell fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2 mb-2">
                        <div>
                            <div class="fw-semibold">Trend Surat Masuk</div>
                            <div class="text-muted small">Total surat masuk per hari (7 hari)</div>
                        </div>
                        <div class="text-muted small">
                            Masuk hari ini:
                            <span class="fw-semibold">{{ $todayIncoming }}</span>
                            <span class="text-muted">•</span>
                            Selesai:
                            <span class="fw-semibold">{{ $suratMasukSelesai }}</span>
                        </div>
                    </div>
                    <div style="height: 320px;">
                        <canvas id="incomingChart" class="w-100 h-100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <div class="fw-semibold">Surat Diproses (Perlu Disposisi)</div>
                            <div class="text-muted small">Daftar surat masuk yang belum diselesaikan disposisi.</div>
                        </div>
                        <a href="{{ route('surat-masuk.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-list-ul me-1"></i>
                            Lihat semua
                        </a>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nomor</th>
                                    <th style="width: 110px;">Tanggal</th>
                                    <th>Pengirim</th>
                                    <th style="width: 110px;">Status</th>
                                    <th class="text-end" style="width: 90px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($stats['recent_surat_masuk_diproses'] ?? []) as $sm)
                                    <tr>
                                        <td class="fw-semibold">{{ $sm->nomor_surat }}</td>
                                        <td>{{ optional($sm->tanggal_surat)->format('Y-m-d') }}</td>
                                        <td class="text-truncate" style="max-width: 220px;">{{ $sm->pengirim }}</td>
                                        @php($smStatusLower = strtolower(trim((string) ($sm->status ?? ''))))
                                        @php($smBadge = match ($smStatusLower) {
                                            'selesai' => 'success',
                                            'diproses' => 'warning text-dark',
                                            'ditolak' => 'danger',
                                            default => 'secondary',
                                        })
                                        <td>
                                            <span class="badge text-bg-{{ $smBadge }} text-uppercase">{{ $sm->status ?? '-' }}</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-1">
                                                <a href="{{ route('surat-masuk.show', $sm) }}" class="btn btn-sm btn-outline-primary" title="Detail" aria-label="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('surat-masuk.disposisi.create', $sm) }}" class="btn btn-sm btn-primary" title="Buat Disposisi" aria-label="Buat Disposisi">
                                                    <i class="bi bi-send-check"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Tidak ada surat diproses.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <div class="fw-semibold">Disposisi Terakhir</div>
                            <div class="text-muted small">Riwayat disposisi yang sudah dikirim.</div>
                        </div>
                        <a href="{{ route('disposisi.outbox') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-list-ul me-1"></i>
                            Lihat semua
                        </a>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Surat</th>
                                    <th>Tujuan</th>
                                    <th class="text-end" style="width: 90px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($stats['recent_disposisi_keluar'] ?? []) as $d)
                                    <tr>
                                        <td class="text-truncate" style="max-width: 180px;">
                                            {{ $d->suratMasuk?->nomor_surat ?? '-' }}
                                            <span class="text-muted">•</span>
                                            {{ $d->suratMasuk?->perihal ?? '-' }}
                                        </td>
                                        <td class="text-truncate" style="max-width: 140px;">
                                            {{ $d->keUser?->name ?? '-' }}
                                        </td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('disposisi.print', $d) }}" target="_blank" title="Lembar Disposisi" aria-label="Lembar Disposisi">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada disposisi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <div class="fw-semibold">Notifikasi Terbaru</div>
                        <div class="text-muted small">Update terakhir untuk akun kamu.</div>
                        <div class="list-group list-group-flush mt-2">
                            @forelse(($stats['recent_notifikasi'] ?? []) as $n)
                                @php($isUnread = $n->status === 'unread')
                                <div class="list-group-item px-0">
                                    <div class="small fw-semibold">{{ $n->pesan }}</div>
                                    <div class="text-muted small">
                                        {{ $n->created_at?->format('Y-m-d H:i') }}
                                        <span class="text-muted">•</span>
                                        <span class="badge text-bg-{{ $isUnread ? 'warning text-dark' : 'secondary' }}">{{ $n->status }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted small py-2">Belum ada notifikasi.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const labels = @json($stats['labels']);
        const suratMasuk = @json($stats['surat_masuk_daily']);

        const ctx = document.getElementById('incomingChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Surat Masuk',
                    data: suratMasuk,
                    borderColor: '#0b2d5b',
                    backgroundColor: 'rgba(11,45,91,0.12)',
                    tension: 0.35,
                    fill: true,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, boxHeight: 10, padding: 12 } },
                    tooltip: { intersect: false, mode: 'index' },
                },
                interaction: { intersect: false, mode: 'index' },
                scales: {
                    x: { grid: { display: false }, ticks: { maxRotation: 0, autoSkip: true } },
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                },
            },
        });
    </script>
@endsection
