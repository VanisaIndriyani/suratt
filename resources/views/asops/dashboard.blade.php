@extends('layouts.asops')

@section('content')
    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Dashboard Asops</div>
            <div class="text-muted">Melihat surat masuk sesuai disposisi dan menerima disposisi.</div>
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

    @php($todayIncoming = $stats['surat_masuk_daily'][count($stats['surat_masuk_daily']) - 1] ?? 0)

    <div class="row g-3">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">Disposisi Masuk</div>
                            <div class="display-6 fw-semibold">{{ $stats['my_disposisi_masuk'] }}</div>
                            <div class="text-muted small">Untuk akun kamu</div>
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
                            <div class="text-muted small">Masuk Hari Ini</div>
                            <div class="display-6 fw-semibold">{{ $todayIncoming }}</div>
                            <div class="text-muted small">Surat masuk</div>
                        </div>
                        <div class="rounded-3 bg-info-subtle text-info p-2">
                            <i class="bi bi-calendar2-check fs-4"></i>
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
                            <div class="display-6 fw-semibold">{{ $stats['surat_masuk_total'] }}</div>
                            <div class="text-muted small">Total (global)</div>
                        </div>
                        <div class="rounded-3 bg-success-subtle text-success p-2">
                            <i class="bi bi-inbox fs-4"></i>
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
                            <div class="fw-semibold">Trend Surat Masuk</div>
                            <div class="text-muted small">Total surat masuk per hari (7 hari)</div>
                        </div>
                        <div class="text-muted small">
                            Total 7 hari:
                            <span class="fw-semibold">{{ array_sum($stats['surat_masuk_daily']) }}</span>
                        </div>
                    </div>
                    <div style="height: 320px;">
                        <canvas id="incomingChart" class="w-100 h-100"></canvas>
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
