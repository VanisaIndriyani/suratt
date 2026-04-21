@extends('layouts.asmin')

@section('content')
    @php($statusCounts = $stats['surat_masuk_untuk_saya_status_counts'] ?? [])
    @php($suratDiproses = (int) ($statusCounts['diproses'] ?? 0))
    @php($suratSelesai = (int) ($statusCounts['selesai'] ?? 0))

    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Dashboard</div>
            <div class="text-muted">Ringkasan disposisi masuk dan surat yang ditujukan ke akun kamu.</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('disposisi.inbox') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left-right me-1"></i>
                Disposisi Masuk
            </a>
            <a href="{{ route('notifikasi.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-bell me-1"></i>
                Notifikasi
            </a>
            <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-inbox me-1"></i>
                Surat Masuk
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">Disposisi Masuk</div>
                            <div class="display-6 fw-semibold">{{ $stats['my_disposisi_masuk'] }}</div>
                            <div class="text-muted small">Total diterima</div>
                        </div>
                        <div class="rounded-3 bg-primary-subtle text-primary p-2">
                            <i class="bi bi-arrow-left-right fs-4"></i>
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
                            <div class="display-6 fw-semibold">{{ $stats['my_notifikasi_unread'] }}</div>
                            <div class="text-muted small">Belum dibaca</div>
                        </div>
                        <div class="rounded-3 bg-warning-subtle text-warning p-2">
                            <i class="bi bi-bell fs-4"></i>
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
                            <div class="text-muted small">Surat Masuk</div>
                            <div class="display-6 fw-semibold">{{ $stats['surat_masuk_untuk_saya'] ?? 0 }}</div>
                            <div class="text-muted small">Ditujukan ke kamu</div>
                        </div>
                        <div class="rounded-3 bg-info-subtle text-info p-2">
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
                            <div class="text-muted small">Status Surat (Kamu)</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <div class="h2 mb-0 fw-semibold">{{ $suratSelesai }}</div>
                                <div class="text-muted small">selesai</div>
                            </div>
                            <div class="text-muted small">diproses: {{ $suratDiproses }}</div>
                        </div>
                        <div class="rounded-3 bg-success-subtle text-success p-2">
                            <i class="bi bi-check2-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-2">
                        <div>
                            <div class="fw-semibold">Aktivitas 7 Hari</div>
                            <div class="text-muted small">Surat masuk vs surat keluar (global)</div>
                        </div>
                        <div class="text-muted small">
                            Total 7 hari:
                            <span class="fw-semibold">{{ array_sum($stats['surat_masuk_daily']) + array_sum($stats['surat_keluar_daily']) }}</span>
                        </div>
                    </div>
                    <div style="height: 300px;">
                        <canvas id="incomingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <div class="fw-semibold">Disposisi Terbaru</div>
                            <div class="text-muted small">Daftar disposisi terakhir yang masuk.</div>
                        </div>
                        <a href="{{ route('disposisi.inbox') }}" class="btn btn-sm btn-outline-secondary">Lihat semua</a>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 110px;">Tanggal</th>
                                    <th>Dari</th>
                                    <th>Surat</th>
                                    <th class="text-end" style="width: 110px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($stats['recent_disposisi_masuk'] ?? []) as $d)
                                    <tr>
                                        <td>{{ optional($d->tanggal_disposisi)->format('Y-m-d') }}</td>
                                        <td class="text-truncate" style="max-width: 160px;">{{ $d->dariUser?->name ?? '-' }}</td>
                                        <td class="text-truncate" style="max-width: 220px;">
                                            {{ $d->suratMasuk?->nomor_surat ?? '-' }}
                                            <span class="text-muted">•</span>
                                            {{ $d->suratMasuk?->perihal ?? '-' }}
                                        </td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('disposisi.print', $d) }}">Lembar</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada disposisi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <div class="fw-semibold">Surat Masuk (Kamu) Terbaru</div>
                            <div class="text-muted small">Surat masuk yang ada disposisi untuk kamu.</div>
                        </div>
                        <a href="{{ route('surat-masuk.index') }}" class="btn btn-sm btn-outline-secondary">Lihat semua</a>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nomor</th>
                                    <th style="width: 110px;">Tanggal</th>
                                    <th>Pengirim</th>
                                    <th style="width: 110px;">Status</th>
                                    <th class="text-end" style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($stats['recent_surat_masuk_untuk_saya'] ?? []) as $sm)
                                    <tr>
                                        <td class="fw-semibold">{{ $sm->nomor_surat }}</td>
                                        <td>{{ optional($sm->tanggal_surat)->format('Y-m-d') }}</td>
                                        <td class="text-truncate" style="max-width: 200px;">{{ $sm->pengirim }}</td>
                                        <td class="text-uppercase">{{ $sm->status ?? '-' }}</td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('surat-masuk.show', $sm) }}">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada surat untuk kamu.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const labels = @json($stats['labels']);
        const suratMasuk = @json($stats['surat_masuk_daily']);
        const suratKeluar = @json($stats['surat_keluar_daily']);

        const ctx = document.getElementById('incomingChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Surat Masuk', data: suratMasuk, backgroundColor: 'rgba(11,45,91,0.7)' },
                    { label: 'Surat Keluar', data: suratKeluar, backgroundColor: 'rgba(13,110,253,0.7)' },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            },
        });
    </script>
@endsection
