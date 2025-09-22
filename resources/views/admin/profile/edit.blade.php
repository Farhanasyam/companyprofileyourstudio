@extends('admin.layout')

@section('title', 'Edit Profil')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Profil Admin</h2>
    <div class="text-muted">
        <i class="bi bi-person-circle me-2"></i>Kelola informasi akun Anda
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-gear me-2"></i>Informasi Profil
                </h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.profile.update') }}" method="POST" id="profileForm">
                    @csrf
                    @method('PUT')

                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-1"></i>Nama Lengkap
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>Email
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Section -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-lock me-2"></i>Ubah Password
                                <small class="text-muted">(Opsional - Kosongkan jika tidak ingin mengubah)</small>
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Current Password -->
                            <div class="mb-3">
                                <label for="current_password" class="form-label">
                                    <i class="bi bi-key me-1"></i>Password Lama
                                </label>
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Wajib diisi jika ingin mengubah password</div>
                            </div>

                            <!-- New Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-key-fill me-1"></i>Password Baru
                                </label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Minimal 8 karakter</div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-key-fill me-1"></i>Konfirmasi Password Baru
                                </label>
                                <input type="password" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" 
                                       name="password_confirmation">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Information Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Akun
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Role:</strong> 
                            <span class="badge bg-success">{{ ucfirst($user->role) }}</span>
                        </p>
                        <p class="mb-2">
                            <strong>Status:</strong> 
                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Bergabung:</strong> 
                            {{ $user->created_at->format('d M Y, H:i') }}
                        </p>
                        <p class="mb-2">
                            <strong>Terakhir Diperbarui:</strong> 
                            {{ $user->updated_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Form validation
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const currentPassword = document.getElementById('current_password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        // If password is provided, current password is required
        if (password && !currentPassword) {
            e.preventDefault();
            Swal.fire({
                title: 'Password Lama Diperlukan',
                text: 'Silakan masukkan password lama untuk mengubah password.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }

        // Check password confirmation
        if (password && password !== confirmPassword) {
            e.preventDefault();
            Swal.fire({
                title: 'Password Tidak Cocok',
                text: 'Password baru dan konfirmasi password tidak sama.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return false;
        }

        // Show confirmation dialog
        e.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Perubahan',
            text: 'Apakah Anda yakin ingin menyimpan perubahan profil?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    // Password visibility toggle
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.querySelector(`[onclick="togglePasswordVisibility('${inputId}')"] i`);
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    // Add password visibility toggles
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInputs = ['current_password', 'password', 'password_confirmation'];
        
        passwordInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                const wrapper = input.parentNode;
                wrapper.style.position = 'relative';
                
                const toggleBtn = document.createElement('button');
                toggleBtn.type = 'button';
                toggleBtn.className = 'btn btn-outline-secondary position-absolute end-0 top-50 translate-middle-y me-2';
                toggleBtn.style.border = 'none';
                toggleBtn.style.background = 'transparent';
                toggleBtn.onclick = () => togglePasswordVisibility(inputId);
                toggleBtn.innerHTML = '<i class="bi bi-eye"></i>';
                
                wrapper.appendChild(toggleBtn);
            }
        });
    });
</script>
@endsection
