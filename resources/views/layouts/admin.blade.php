<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Admin' }} - {{ config('app.name') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <style>
            :root { --sidebar-width: 260px; }
            @media (min-width: 992px) {
                .app-content { margin-left: var(--sidebar-width); }
            }
            .sidebar-desktop { width: var(--sidebar-width); }
            .bg-navy { background: linear-gradient(180deg, #0b2d5b 0%, #071a33 100%) !important; }
            .sidebar-desktop { overflow-y: auto; box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.25); }
            .nav-link.sidebar-link { color: rgba(255,255,255,.9); border-radius: .6rem; transition: background-color .15s ease, color .15s ease, transform .15s ease; }
            .nav-link.sidebar-link:hover { background-color: rgba(255,255,255,.12); color: #fff; transform: translateX(2px); }
            .nav-link.sidebar-link.active { background-color: #081f3f; color: #fff; box-shadow: inset 0 0 0 1px rgba(255,255,255,.08); }
            .nav-link.sidebar-link i { width: 1.25rem; text-align: center; }
            .table-responsive { -webkit-overflow-scrolling: touch; }
        </style>
    </head>
    <body class="bg-light">
        <nav class="navbar navbar-dark bg-navy sticky-top d-lg-none">
            <div class="container-fluid">
                <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile" aria-controls="sidebarMobile">
                    Menu
                </button>
                <span class="navbar-brand ms-2">Sisfo Surat Gartap</span>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light" type="submit">Logout</button>
                </form>
            </div>
        </nav>

        <div class="offcanvas offcanvas-start bg-navy text-white d-lg-none" tabindex="-1" id="sidebarMobile" aria-labelledby="sidebarMobileLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="sidebarMobileLabel">Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="text-center mb-3">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" width="56" height="56" class="rounded">
                    <div class="fw-semibold mt-2">Sisfo Surat Gartap</div>
                </div>
                <div class="fw-semibold text-center">{{ auth()->user()->name }}</div>
                <div class="small text-white-50 mb-2 text-center">{{ '@'.auth()->user()->username }} • Admin</div>
                <hr class="border-light opacity-25 my-3">
                <div class="nav nav-pills flex-column gap-1">
                    <a class="nav-link sidebar-link d-flex align-items-center gap-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a class="nav-link sidebar-link d-flex align-items-center gap-2 {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}" href="{{ route('surat-masuk.index') }}">
                        <i class="bi bi-inbox"></i>
                        <span>Surat Masuk</span>
                    </a>
                    <a class="nav-link sidebar-link d-flex align-items-center gap-2 {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}" href="{{ route('surat-keluar.index') }}">
                        <i class="bi bi-send"></i>
                        <span>Surat Keluar</span>
                    </a>
                    <a class="nav-link sidebar-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people"></i>
                        <span>Users</span>
                    </a>
                </div>
            </div>
        </div>

        <aside class="sidebar-desktop d-none d-lg-flex flex-column position-fixed top-0 start-0 h-100 bg-navy text-white p-3">
            <div class="text-center mb-3">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" width="60" height="60" class="rounded">
                <div class="fw-semibold mt-2">Sisfo Surat Gartap</div>
            </div>

            <div class="fw-semibold text-center">{{ auth()->user()->name }}</div>
            <div class="small text-white-50 mb-2 text-center">{{ '@'.auth()->user()->username }} • Admin</div>
            <hr class="border-light opacity-25 my-3">
            <div class="nav nav-pills flex-column gap-1">
                <a class="nav-link sidebar-link d-flex align-items-center gap-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a class="nav-link sidebar-link d-flex align-items-center gap-2 {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}" href="{{ route('surat-masuk.index') }}">
                    <i class="bi bi-inbox"></i>
                    <span>Surat Masuk</span>
                </a>
                <a class="nav-link sidebar-link d-flex align-items-center gap-2 {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}" href="{{ route('surat-keluar.index') }}">
                    <i class="bi bi-send"></i>
                    <span>Surat Keluar</span>
                </a>
                <a class="nav-link sidebar-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </div>

            <div class="mt-auto">
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light w-100" type="submit">Logout</button>
                </form>
            </div>
        </aside>

        <main class="app-content p-2 p-md-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif (session('status'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-1"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title fw-semibold">Konfirmasi</div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="confirmModalMessage">Yakin?</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="confirmModalOk">
                            <i class="bi bi-trash me-1"></i>
                            Ya, hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            let confirmForm = null;

            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (!(form instanceof HTMLFormElement)) return;
                const message = form.getAttribute('data-confirm');
                if (!message) return;

                e.preventDefault();
                confirmForm = form;

                const el = document.getElementById('confirmModal');
                const msgEl = document.getElementById('confirmModalMessage');
                const okEl = document.getElementById('confirmModalOk');
                if (!el || !msgEl || !okEl) return;

                msgEl.textContent = message;
                okEl.onclick = function () {
                    if (confirmForm) confirmForm.submit();
                };

                const modal = bootstrap.Modal.getOrCreateInstance(el);
                modal.show();
            }, true);
        </script>
    </body>
</html>
