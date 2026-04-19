@php($layout = auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.'.auth()->user()->role)
@extends($layout)

@section('content')
    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Notifikasi</div>
            <div class="text-muted">Notifikasi disposisi dan aktivitas.</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('disposisi.inbox') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left-right me-1"></i>
                Disposisi Masuk
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            @php($no = $items->firstItem() ?? 1)
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">No</th>
                        <th>Pesan</th>
                        <th style="width: 160px;">Waktu</th>
                        <th style="width: 110px;">Status</th>
                        <th class="text-end" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $n)
                        @php($isUnread = $n->status === 'unread')
                        @php($badge = $isUnread ? 'warning text-dark' : 'secondary')
                        @php($firstLink = null)
                        @php(preg_match('/https?:\\/\\/\\S+/', (string) $n->pesan, $m) ? $firstLink = $m[0] : null)
                        <tr class="{{ $isUnread ? 'table-warning' : '' }}">
                            <td>{{ $no + $loop->index }}</td>
                            <td class="text-break">
                                <div class="fw-semibold">{{ $n->pesan }}</div>
                                @if($firstLink)
                                    <div class="small">
                                        <a class="link-primary text-decoration-none" href="{{ $firstLink }}" target="_blank" rel="noopener">
                                            <i class="bi bi-box-arrow-up-right me-1"></i>
                                            Buka link
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $n->created_at?->format('Y-m-d H:i') }}</td>
                            <td><span class="badge text-bg-{{ $badge }}">{{ $n->status }}</span></td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    @if($firstLink)
                                        <a class="btn btn-sm btn-outline-primary" href="{{ $firstLink }}" target="_blank" rel="noopener" title="Buka link" aria-label="Buka link">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    @endif
                                    @if($isUnread)
                                        <form method="post" action="{{ route('notifikasi.read', $n) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success" type="submit" title="Tandai dibaca" aria-label="Tandai dibaca">
                                                <i class="bi bi-check2"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">Belum ada notifikasi.</td>
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
