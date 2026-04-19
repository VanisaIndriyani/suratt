@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Tambah User</div>
            <div class="text-muted">Buat akun baru.</div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="post" action="{{ route('admin.users.store') }}" class="vstack gap-3">
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nama</label>
                        <input name="name" value="{{ old('name') }}" class="form-control" required>
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Username</label>
                        <input name="username" value="{{ old('username') }}" class="form-control" required>
                        @error('username') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                        @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">No HP</label>
                        <input name="no_hp" value="{{ old('no_hp') }}" class="form-control">
                        @error('no_hp') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            @foreach(['staf','asmin','asops','kasatker','kaskogartap'] as $r)
                                <option value="{{ $r }}" @selected(old('role','staf') === $r)>{{ $r }}</option>
                            @endforeach
                        </select>
                        @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
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
