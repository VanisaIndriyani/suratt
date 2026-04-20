@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Ubah User</div>
            <div class="text-muted">{{ $item->name }} ({{ $item->username }})</div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="post" action="{{ route('admin.users.update', $item) }}" class="vstack gap-3">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nama</label>
                        <input name="name" value="{{ old('name', $item->name) }}" class="form-control" required>
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Username</label>
                        <input name="username" value="{{ old('username', $item->username) }}" class="form-control" required>
                        @error('username') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $item->email) }}" class="form-control" required>
                        @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">No HP</label>
                        <input name="no_hp" value="{{ old('no_hp', $item->no_hp) }}" class="form-control">
                        @error('no_hp') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Jabatan</label>
                        <select name="jabatan" class="form-select" required>
                            @foreach($jabatanOptions as $j)
                                <option value="{{ $j }}" @selected(old('jabatan', $item->jabatan ?: strtoupper($item->role)) === $j)>{{ $j }}</option>
                            @endforeach
                        </select>
                        @error('jabatan') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Password Baru (opsional)</label>
                        <input type="password" name="password" class="form-control">
                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
