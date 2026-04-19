@php($role = strtolower(trim((string) auth()->user()->role)))
@php($layout = $role === 'admin' ? 'layouts.admin' : 'layouts.'.$role)
@extends($layout)

@section('content')
    <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Tambah Surat Masuk</div>
            <div class="text-muted">Input data surat masuk baru.</div>
        </div>
        <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="post" action="{{ route('surat-masuk.store') }}" enctype="multipart/form-data" class="vstack gap-3">
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nomor Surat</label>
                        <input name="nomor_surat" value="{{ old('nomor_surat') }}" class="form-control" required>
                        @error('nomor_surat') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}" class="form-control" required>
                        @error('tanggal_surat') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Tanggal Terima</label>
                        <input type="date" name="tanggal_terima" value="{{ old('tanggal_terima') }}" class="form-control">
                        @error('tanggal_terima') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Pengirim</label>
                        <input name="pengirim" value="{{ old('pengirim') }}" class="form-control" required>
                        @error('pengirim') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Jenis Surat</label>
                        <input name="jenis_surat" value="{{ old('jenis_surat') }}" class="form-control">
                        @error('jenis_surat') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Perihal</label>
                        <input name="perihal" value="{{ old('perihal') }}" class="form-control" required>
                        @error('perihal') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">File Surat (PDF/DOC)</label>
                    <input type="file" name="file_surat" class="form-control">
                    @error('file_surat') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
