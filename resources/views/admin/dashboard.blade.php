@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Dashboard</div>
            <div class="text-muted">Kelola surat masuk, surat keluar, dan user.</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-inbox me-1"></i>
                Surat Masuk
            </a>
            <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-send me-1"></i>
                Surat Keluar
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                <i class="bi bi-people me-1"></i>
                Users
            </a>
        </div>
    </div>

    <div class="row g-3 mb-1">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">Surat Masuk</div>
                            <div class="display-6 fw-semibold mb-0">{{ $stats['surat_masuk_total'] }}</div>
                        </div>
                        <div class="rounded-3 bg-primary-subtle text-primary p-2">
                            <i class="bi bi-inbox fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">Surat Keluar</div>
                            <div class="display-6 fw-semibold mb-0">{{ $stats['surat_keluar_total'] }}</div>
                        </div>
                        <div class="rounded-3 bg-success-subtle text-success p-2">
                            <i class="bi bi-send fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">Total User</div>
                            <div class="display-6 fw-semibold mb-0">{{ $stats['user_total'] }}</div>
                        </div>
                        <div class="rounded-3 bg-info-subtle text-info p-2">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">Aktivitas 7 Hari</div>
                            <div class="display-6 fw-semibold mb-0">{{ array_sum($stats['surat_masuk_daily']) + array_sum($stats['surat_keluar_daily']) }}</div>
                        </div>
                        <div class="rounded-3 bg-warning-subtle text-warning p-2">
                            <i class="bi bi-graph-up fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-1">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-2">
                        <div>
                            <div class="fw-semibold">Aktivitas Surat</div>
                            <div class="text-muted small">Surat masuk vs surat keluar (7 hari)</div>
                        </div>
                        <div class="text-muted small">
                            Total 7 hari:
                            <span class="fw-semibold">{{ array_sum($stats['surat_masuk_daily']) + array_sum($stats['surat_keluar_daily']) }}</span>
                        </div>
                    </div>
                    <div style="height: 280px;">
                        <canvas id="activityChart" class="w-100 h-100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="fw-semibold">Komposisi Role</div>
                    <div class="text-muted small mb-2">Jumlah user per role</div>
                    <div style="height: 280px;">
                        <canvas id="roleChart" class="w-100 h-100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @php($roles = ['staf' => 'primary', 'asmin' => 'info', 'asops' => 'secondary', 'kasatker' => 'success', 'kaskogartap' => 'warning'])
        @foreach($roles as $role => $color)
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small text-uppercase">{{ $role }}</div>
                                <div class="display-6 fw-semibold mb-0">{{ $stats['role_counts'][$role] ?? 0 }}</div>
                            </div>
                            <div class="rounded-3 bg-{{ $color }}-subtle text-{{ $color }} p-2">
                                <i class="bi bi-person-badge fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const roleLabels = Object.keys(@json($stats['role_counts']));
        const roleTotals = Object.values(@json($stats['role_counts']));

        const ctx = document.getElementById('roleChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: roleLabels,
                datasets: [{
                    data: roleTotals,
                    backgroundColor: ['#0b2d5b', '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1'],
                    borderWidth: 0,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, boxHeight: 10, padding: 12 },
                    },
                },
            },
        });
    </script>

    <script>
        const labels = @json($stats['labels']);
        const suratMasukDaily = @json($stats['surat_masuk_daily']);
        const suratKeluarDaily = @json($stats['surat_keluar_daily']);

        const ctxActivity = document.getElementById('activityChart');
        new Chart(ctxActivity, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: suratMasukDaily,
                        borderColor: '#0b2d5b',
                        backgroundColor: 'rgba(11,45,91,0.12)',
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Surat Keluar',
                        data: suratKeluarDaily,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13,110,253,0.12)',
                        tension: 0.35,
                        fill: true,
                    },
                ],
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
