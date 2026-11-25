@php
    $companyName = \App\Models\Setting::get('company_name', 'YourStudio');
    $logoUrl = \App\Helpers\BrandHelper::logoUrl();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Masuk Admin — {{ $companyName }}</title>
    <link rel="icon" href="/favicon.ico">
    <link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --yellow: #fde781;
            --light-yellow: #fef5ce;
            --cream: #fffaf0;
            --dark-brown: #553914;
            --light-brown: #d39f69;
            --ink-muted: #7a6a58;
            --line: rgba(142, 99, 55, 0.22);
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            background:
                radial-gradient(circle at 15% 20%, rgba(253, 231, 129, 0.55), transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(211, 159, 105, 0.45), transparent 50%),
                var(--cream);
            color: var(--dark-brown);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .login-shell {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 22px;
            box-shadow: 0 24px 60px -24px rgba(85, 57, 20, 0.35);
            padding: 2.5rem 2.25rem 2rem;
        }

        .login-brand {
            text-align: center;
            margin-bottom: 1.75rem;
        }
        .login-brand__logo {
            width: 64px;
            height: 64px;
            margin: 0 auto 0.9rem;
            border-radius: 18px;
            background: var(--light-yellow);
            border: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .login-brand__logo img {
            max-width: 78%;
            max-height: 78%;
            object-fit: contain;
        }
        .login-brand__logo span {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-brown);
        }
        .login-brand h1 {
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0;
        }
        .login-brand p {
            margin: 0.25rem 0 0;
            font-size: 0.9rem;
            color: var(--ink-muted);
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
        }
        .login-input {
            position: relative;
        }
        .login-input > .bi {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ink-muted);
            pointer-events: none;
        }
        .login-input .form-control {
            height: 48px;
            padding-left: 2.6rem;
            border-radius: 12px;
            border-color: var(--line);
            background: #fff;
            color: var(--dark-brown);
        }
        .login-input .form-control:focus {
            border-color: var(--light-brown);
            box-shadow: 0 0 0 0.2rem rgba(211, 159, 105, 0.25);
        }
        .login-input .form-control.is-invalid {
            border-color: #dc3545;
            background-image: none;
        }
        .login-input--password .form-control {
            padding-right: 3rem;
        }
        .login-toggle {
            position: absolute;
            right: 0.35rem;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: var(--ink-muted);
        }
        .login-toggle:hover,
        .login-toggle:focus-visible {
            background: var(--light-yellow);
            color: var(--dark-brown);
            outline: none;
        }

        .form-check-input:checked {
            background-color: var(--dark-brown);
            border-color: var(--dark-brown);
        }
        .form-check-input:focus {
            border-color: var(--light-brown);
            box-shadow: 0 0 0 0.2rem rgba(211, 159, 105, 0.25);
        }
        .form-check-label {
            font-size: 0.875rem;
            color: var(--ink-muted);
        }

        .btn-login {
            height: 48px;
            border: 0;
            border-radius: 12px;
            background: var(--dark-brown);
            color: var(--yellow);
            font-weight: 600;
            transition: background 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-login:hover,
        .btn-login:focus-visible {
            background: #3f2a0e;
            color: var(--yellow);
            box-shadow: 0 8px 20px -8px rgba(85, 57, 20, 0.6);
        }
        .btn-login:disabled {
            opacity: 0.8;
            color: var(--yellow);
        }

        .login-alert {
            display: flex;
            gap: 0.6rem;
            align-items: flex-start;
            padding: 0.75rem 0.9rem;
            margin-bottom: 1.25rem;
            border-radius: 12px;
            background: #fff4f4;
            border: 1px solid rgba(220, 53, 69, 0.25);
            color: #9b1c2b;
            font-size: 0.875rem;
        }

        .login-back {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 1.25rem;
            font-size: 0.875rem;
            color: var(--ink-muted);
            text-decoration: none;
        }
        .login-back:hover {
            color: var(--dark-brown);
        }

        @media (max-width: 400px) {
            .login-card { padding: 2rem 1.35rem 1.5rem; }
        }
    </style>
</head>
<body>
    <main class="login-shell">
        <div class="login-card">
            <div class="login-brand">
                <div class="login-brand__logo">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $companyName }}">
                    @else
                        <span>{{ mb_substr($companyName, 0, 1) }}</span>
                    @endif
                </div>
                <h1>{{ $companyName }}</h1>
                <p>Masuk ke panel admin</p>
            </div>

            @if ($errors->any() || session('error'))
                <div class="login-alert" role="alert">
                    <i class="bi bi-exclamation-circle"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        @if (session('error'))
                            <div>{{ session('error') }}</div>
                        @endif
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="login-input">
                        <i class="bi bi-envelope"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}"
                               placeholder="nama@email.com" autocomplete="username" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="login-input login-input--password">
                        <i class="bi bi-lock"></i>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" placeholder="Masukkan password"
                               autocomplete="current-password" required>
                        <button type="button" class="login-toggle" id="togglePassword" aria-label="Tampilkan password" aria-pressed="false">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="btn btn-login w-100" id="loginButton">
                    Masuk
                </button>
            </form>
        </div>

        <div class="text-center">
            <a href="{{ url('/') }}" class="login-back"><i class="bi bi-arrow-left"></i>Kembali ke website</a>
        </div>
    </main>

    <script>
        (function () {
            var input = document.getElementById('password');
            var toggle = document.getElementById('togglePassword');
            toggle.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                toggle.setAttribute('aria-pressed', show ? 'true' : 'false');
                toggle.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
                toggle.innerHTML = '<i class="bi ' + (show ? 'bi-eye-slash' : 'bi-eye') + '"></i>';
                input.focus();
            });

            // Cegah kirim ganda & beri tanda sedang memproses
            var form = document.getElementById('loginForm');
            var button = document.getElementById('loginButton');
            form.addEventListener('submit', function (e) {
                if (!form.email.value.trim() || !form.password.value) {
                    e.preventDefault();
                    (form.email.value.trim() ? form.password : form.email).focus();
                    return;
                }
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses…';
            });
        })();
    </script>
</body>
</html>
