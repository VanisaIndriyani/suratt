@php($role = strtolower(trim((string) auth()->user()->role)))
@php($layout = $role === 'admin' ? 'layouts.admin' : 'layouts.'.$role)
@extends($layout)

@section('content')
    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Detail Surat Keluar</div>
            <div class="text-muted">{{ $item->nomor_surat }}</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline-secondary">Kembali</a>
            <a href="{{ route('surat-keluar.print', $item) }}" class="btn btn-outline-secondary">Cetak</a>
            @if($item->file_surat)
                <a href="{{ route('surat-keluar.download', $item) }}" class="btn btn-outline-secondary">Unduh File</a>
            @endif
            @if(in_array($role, ['staf', 'admin'], true))
                <a href="{{ route('surat-keluar.edit', $item) }}" class="btn btn-primary">Ubah</a>
            @endif
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr>
                                    <th style="width: 180px;">Nomor Surat</th>
                                    <td>{{ $item->nomor_surat }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Surat</th>
                                    <td>{{ optional($item->tanggal_surat)->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>Tujuan</th>
                                    <td>{{ $item->tujuan }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Surat</th>
                                    <td>{{ $item->jenis_surat ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Perihal</th>
                                    <td>{{ $item->perihal }}</td>
                                </tr>
                                <tr>
                                    <th>Barcode</th>
                                    <td class="text-break">
                                        {{ $item->barcode }}
                                        <div class="small mt-1">
                                            <a class="link-primary text-decoration-none" href="{{ route('barcode.show', $item->barcode) }}">{{ route('barcode.show', $item->barcode) }}</a>
                                        </div>
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
                    <div class="fw-semibold">QR untuk Scan</div>
                    <div class="text-muted small mb-3">Scan untuk membuka detail surat</div>
                    <div class="d-flex justify-content-center">
                        <img
                            width="180"
                            height="180"
                            class="rounded border bg-white p-2"
                            src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(route('barcode.show', $item->barcode)) }}"
                            alt="QR"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
