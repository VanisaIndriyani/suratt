<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - Sistem Informasi Pengarsipan Surat Kogartap I/Jakarta</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <style>
            .login-bg {
                background-image: url("{{ asset('img/bg.jpeg') }}");
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                min-height: 100vh;
            }

            .login-card {
                border-radius: 1.25rem;
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.35);
            }

            .brand-badge {
                width: 84px;
                height: 84px;
            }

            .form-label {
                font-weight: 600;
                color: #0f172a;
            }

            .input-group-text {
                background-color: #f1f5f9;
                border-color: #dbe4f0;
                color: #0b2d5b;
            }

            .form-control {
                border-color: #dbe4f0;
            }

            .form-control:focus {
                border-color: rgba(11, 45, 91, 0.55);
                box-shadow: 0 0 0 .2rem rgba(11, 45, 91, 0.12);
            }

            .btn-login {
                background: linear-gradient(90deg, #0b2d5b 0%, #0d6efd 100%);
                border: 0;
                box-shadow: 0 .75rem 1.5rem rgba(11, 45, 91, 0.18);
            }

            .btn-login:hover {
                filter: brightness(1.02);
            }

            .divider {
                height: 1px;
                background: rgba(148, 163, 184, 0.55);
            }
        </style>
    </head>
    <body>
        <div class="login-bg d-flex align-items-center">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-10 col-md-7 col-lg-5">
                        <div class="card login-card shadow-lg border-0">
                            <div class="card-body p-4 p-md-5">
                                <div class="text-center mb-4">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-4 shadow-sm p-2 mb-2 brand-badge">
                                        <img src="{{ asset('img/logo (2).png') }}" alt="Logo" width="60" height="60" class="img-fluid">
                                    </div>
                                    <h1 class="h5 mb-1 fw-semibold">Sistem Informasi Pengarsipan Surat Kogartap I/Jakarta</h1>
                                    <div class="text-muted small">Masuk menggunakan akun kamu</div>
                                </div>

                                <form method="post" action="{{ route('login.store') }}" class="vstack gap-3">
                                    @csrf

                                    <div>
                                        <label class="form-label">Username / Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                                            <input name="login" value="{{ old('login') }}" class="form-control @error('login') is-invalid @enderror" autocomplete="username" required>
                                            @error('login')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required>
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Tampilkan password">
                                                <i class="bi bi-eye" id="toggleIcon"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">Ingat saya</label>
                                        </div>
                                    </div>

                                    <button class="btn btn-login text-white w-100 py-2 fw-semibold" type="submit">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>
                                        Login
                                    </button>
                                </form>
                            </div>
                            <div class="card-footer bg-transparent border-0 text-center small text-muted pb-4">
                                <div class="divider my-2"></div>
                                © {{ date('Y') }} {{ config('app.name') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            const toggleButton = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            toggleButton.addEventListener('click', () => {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        </script>
    </body>
</html>
