@php($role = strtolower(trim((string) auth()->user()->role)))
@php($layout = $role === 'admin' ? 'layouts.admin' : 'layouts.'.$role)
@extends($layout)

@section('content')
    @php($usersNoHpCount = $users->whereNotNull('no_hp')->count())
    @php($usersMissingHpCount = $users->whereNull('no_hp')->count())

    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Buat Disposisi</div>
            <div class="text-muted">Surat masuk: {{ $surat->nomor_surat }} • {{ $surat->perihal }}</div>
        </div>
        <a href="{{ route('surat-masuk.show', $surat) }}" class="btn btn-outline-secondary" title="Kembali" aria-label="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Ringkasan Surat</div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="text-muted" style="width: 160px;">Nomor Surat</th>
                                    <td class="fw-semibold">{{ $surat->nomor_surat }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tanggal Surat</th>
                                    <td>{{ optional($surat->tanggal_surat)->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tanggal Terima</th>
                                    <td>{{ optional($surat->tanggal_terima)->format('Y-m-d') ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Pengirim</th>
                                    <td>{{ $surat->pengirim }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Perihal</th>
                                    <td>{{ $surat->perihal }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <a href="{{ route('surat-masuk.show', $surat) }}" class="btn btn-outline-primary">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            Lihat Detail Surat
                        </a>
                        <a href="{{ route('surat-masuk.print', $surat) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-printer me-1"></i>
                            Cetak
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Form Disposisi</div>

                    <div class="alert alert-info mb-3" role="alert">
                        <i class="bi bi-info-circle me-1"></i>
                        Notifikasi WhatsApp akan dikirim otomatis jika penerima memiliki No HP.
                        <span class="text-muted">({{ $usersNoHpCount }} ada No HP{{ $usersMissingHpCount ? ', '.$usersMissingHpCount.' belum diisi' : '' }})</span>
                    </div>

                    <form method="post" action="{{ route('surat-masuk.disposisi.store', $surat) }}" class="vstack gap-3">
                        @csrf

                        <div>
                            <label class="form-label">Kepada (Penerima)</label>
                            <select name="ke_user_id" class="form-select" required>
                                <option value="">-- pilih penerima --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" @selected(old('ke_user_id') == $u->id)>
                                        {{ $u->name }} ({{ $u->role }}){{ $u->no_hp ? ' - '.$u->no_hp : ' - no hp belum diisi' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ke_user_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="form-label">Instruksi</label>
                            <textarea name="instruksi" rows="5" class="form-control" required placeholder="Tulis instruksi disposisi...">{{ old('instruksi') }}</textarea>
                            @error('instruksi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-send-check me-1"></i>
                                Kirim Disposisi
                            </button>
                            <a href="{{ route('surat-masuk.show', $surat) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i>
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
