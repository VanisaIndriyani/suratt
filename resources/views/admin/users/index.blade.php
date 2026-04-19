@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2 mb-3">
        <div>
            <div class="h4 mb-0">Users</div>
            <div class="text-muted">Kelola akun pengguna dan role.</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Tambah User</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="get" class="row g-2 align-items-center">
                <div class="col-12 col-lg-8">
                    <input name="q" value="{{ $q }}" placeholder="Cari nama/username/email/no hp/role..." class="form-control">
                </div>
                <div class="col-12 col-lg-4 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Role</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php($badge = match ($item->role) {
                            'admin' => 'bg-dark',
                            'staf' => 'bg-primary',
                            'kasatker' => 'bg-success',
                            'kaskogartap' => 'bg-warning text-dark',
                            'asmin' => 'bg-info text-dark',
                            'asops' => 'bg-secondary',
                            default => 'bg-light text-dark',
                        })
                        <tr>
                            <td class="fw-semibold">{{ $item->name }}</td>
                            <td>{{ $item->username }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->no_hp ?: '-' }}</td>
                            <td><span class="badge {{ $badge }}">{{ $item->role }}</span></td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.users.edit', $item) }}" class="btn btn-sm btn-outline-primary" title="Ubah" aria-label="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="post" action="{{ route('admin.users.destroy', $item) }}" data-confirm="Hapus user ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus" aria-label="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">Belum ada user.</td>
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
