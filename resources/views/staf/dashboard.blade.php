@extends('layouts.staf')

@section('content')
    @php($statusCounts = $stats['surat_masuk_status_counts'] ?? [])
    @php($suratMasukDiproses = (int) ($statusCounts['diproses'] ?? 0))
    @php($suratMasukSelesai = (int) ($statusCounts['selesai'] ?? 0))

    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Dashboard</div>
            <div class="text-muted">Ringkasan aktivitas persuratan dan akses cepat input data.</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary">
                <i class="bi bi-inbox me-1"></i>
                Tambah Surat Masuk
            </a>
            <a href="{{ route('surat-keluar.create') }}" class="btn btn-outline-primary">
                <i class="bi bi-send me-1"></i>
                Tambah Surat Keluar
            </a>
        </div>
    </div>

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
                            <div class="text-muted small">Surat Keluar</div>
                            <div class="display-6 fw-semibold">{{ $stats['surat_keluar_total'] }}</div>
                            <div class="text-muted small">Total</div>
                        </div>
                        <div class="rounded-3 bg-success-subtle text-success p-2">
                            <i class="bi bi-send fs-4"></i>
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
                            <div class="text-muted small">Surat Masuk Diproses</div>
                            <div class="display-6 fw-semibold">{{ $suratMasukDiproses }}</div>
                            <div class="text-muted small">Menunggu disposisi</div>
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
                            <div class="text-muted small">Surat Masuk Selesai</div>
                            <div class="display-6 fw-semibold">{{ $suratMasukSelesai }}</div>
                            <div class="text-muted small">Sudah disposisi</div>
                        </div>
                        <div class="rounded-3 bg-info-subtle text-info p-2">
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
                            <div class="text-muted small">Surat masuk vs surat keluar</div>
                        </div>
                        <div class="text-muted small">
                            Total 7 hari:
                            <span class="fw-semibold">{{ array_sum($stats['surat_masuk_daily']) + array_sum($stats['surat_keluar_daily']) }}</span>
                        </div>
                    </div>
                    <div style="height: 320px;">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <div class="fw-semibold">Surat Masuk Terbaru</div>
                            <div class="text-muted small">Update terakhir yang masuk ke sistem.</div>
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
                                @forelse(($stats['recent_surat_masuk'] ?? []) as $sm)
                                    <tr>
                                        <td class="fw-semibold">{{ $sm->nomor_surat }}</td>
                                        <td>{{ optional($sm->tanggal_surat)->format('Y-m-d') }}</td>
                                        <td class="text-truncate" style="max-width: 180px;">{{ $sm->pengirim }}</td>
                                        <td class="text-uppercase">{{ $sm->status ?? '-' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('surat-masuk.show', $sm) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada data.</td>
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
                            <div class="fw-semibold">Surat Keluar Terbaru</div>
                            <div class="text-muted small">Update terakhir yang dibuat staf.</div>
                        </div>
                        <a href="{{ route('surat-keluar.index') }}" class="btn btn-sm btn-outline-secondary">Lihat semua</a>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nomor</th>
                                    <th style="width: 110px;">Tanggal</th>
                                    <th>Tujuan</th>
                                    <th class="text-end" style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($stats['recent_surat_keluar'] ?? []) as $sk)
                                    <tr>
                                        <td class="fw-semibold">{{ $sk->nomor_surat }}</td>
                                        <td>{{ optional($sk->tanggal_surat)->format('Y-m-d') }}</td>
                                        <td class="text-truncate" style="max-width: 220px;">{{ $sk->tujuan }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('surat-keluar.show', $sk) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada data.</td>
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

        const ctx = document.getElementById('activityChart');
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
