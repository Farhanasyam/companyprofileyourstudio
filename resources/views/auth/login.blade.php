<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - YourStudio Admin</title>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --yellow: #fde781;
            --light-yellow: #fef5ce;
            --dark-brown: #553914;
            --orange: #ef9e46;
            --light-pink: #fec9d3;
            --light-brown: #d39f69;
            --white: #ffffff;
            --light-grey: #f8f8f8;
            --dark-grey: #313131;
        }
        
        body {
            background: linear-gradient(135deg, var(--yellow) 0%, var(--light-brown) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--light-brown);
        }
        .logo {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--dark-brown);
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background: var(--light-brown);
            border-color: var(--light-brown);
            color: var(--white);
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: var(--dark-brown);
            border-color: var(--dark-brown);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(85, 57, 20, 0.3);
        }
        .form-control:focus {
            border-color: var(--light-brown);
            box-shadow: 0 0 0 0.2rem rgba(211, 159, 105, 0.25);
        }
        .form-label {
            color: var(--dark-brown);
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card login-card border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="logo">YourStudio</div>
                            <h4 class="text-muted">Admin Panel</h4>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">
                                Login
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <small class="text-muted">
                                Default Admin: admin@yourstudio.com / admin123
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
