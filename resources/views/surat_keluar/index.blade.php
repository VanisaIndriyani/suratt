@php($role = strtolower(trim((string) auth()->user()->role)))
@php($layout = $role === 'admin' ? 'layouts.admin' : 'layouts.'.$role)
@extends($layout)

@section('content')
    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Surat Keluar</div>
            <div class="text-muted">Kelola data surat keluar.</div>
        </div>
        @if(in_array($role, ['staf', 'admin'], true))
            <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary">Tambah</a>
        @endif
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="get" class="row g-2 align-items-center">
                <div class="col-12 col-lg-5">
                    <input name="q" value="{{ $q }}" placeholder="Cari nomor/perihal/tujuan/barcode..." class="form-control">
                </div>
                <div class="col-12 col-lg-5 d-flex gap-2">
                    <input type="date" name="tanggal_from" value="{{ $tanggalFrom ?? '' }}" class="form-control" title="Tanggal dari">
                    <input type="date" name="tanggal_to" value="{{ $tanggalTo ?? '' }}" class="form-control" title="Tanggal sampai">
                </div>
                <div class="col-12 col-lg-2 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Nomor</th>
                        <th>Tanggal</th>
                        <th>Tujuan</th>
                        <th>Perihal</th>
                        <th>Barcode</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $no + $loop->index }}</td>
                            <td class="fw-semibold">{{ $item->nomor_surat }}</td>
                            <td>{{ optional($item->tanggal_surat)->format('Y-m-d') }}</td>
                            <td>{{ $item->tujuan }}</td>
                            <td>{{ $item->perihal }}</td>
                            <td>
                                <a href="{{ route('barcode.show', $item->barcode) }}" class="link-primary text-decoration-none">
                                    {{ \Illuminate\Support\Str::limit($item->barcode, 18) }}
                                </a>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('surat-keluar.show', $item) }}" class="btn btn-sm btn-outline-primary" title="Detail" aria-label="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(in_array($role, ['staf', 'admin'], true))
                                        <a href="{{ route('surat-keluar.edit', $item) }}" class="btn btn-sm btn-outline-secondary" title="Ubah" aria-label="Ubah">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="post" action="{{ route('surat-keluar.destroy', $item) }}" data-confirm="Hapus surat ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus" aria-label="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Belum ada data.</td>
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
