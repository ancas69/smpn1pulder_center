@extends('templates.login_templates')
@section('content')
    <div class="bg-animation">
        <span></span><span></span><span></span><span></span><span></span>
    </div>

    <a class="btn-back" href="{{ route('home') }}">Kembali</a>

    <div class="login-container">
        <div class="login-card">
            <!-- Logo & Judul -->
            <div class="login-header">
                <div class="logo">
                    <img src="{{ asset('images/logo_smp.png') }}"></img>
                </div>
                <h1>SMP Negeri 1 Pulau Derawan</h1>
                <p>Silakan daftarkan akun anda</p>
            </div>

            <!-- Form -->
            <form id="registerForm">
                @csrf
                <!-- Username -->
                <div class="input-group">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" id="username" placeholder=" " name="username" autocomplete="username" required />
                    <label for="username">Username</label>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" id="email" placeholder=" " name="email" autocomplete="email" required />
                    <label for="email">Email</label>
                </div>

                <!-- Password -->
                <div class="input-group">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="password" placeholder=" " name="password" autocomplete="current-password"
                        required />
                    <label for="password">Password</label>
                    <button type="button" class="toggle-password" id="lihatPW" aria-label="Tampilkan password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>

                <!-- Password -->
                <div class="input-group">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="repassword" placeholder=" " name="repassword" autocomplete="current-password"
                        required />
                    <label for="repassword">Konfirmasi Password</label>
                    <button type="button" class="toggle-password" id="lihatPW2" aria-label="Tampilkan password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>



                <!-- Tombol -->
                <button type="submit" class="btn-primary" id="btnRegister">
                    <span class="btn-text">Daftar</span>
                    <i class="fa-solid fa-arrow-right btn-icon"></i>
                </button>

                <p class="text-center mt-3">Sudah punya akun? <a href="{{ route('login') }}"
                        style="text-decoration: underline; color: white;">Masuk</a></p>

                <p class="">
                    @if (session('error'))
                        {{ session('error') }}
                    @endif
                </p>

                <!-- Pesan error -->
                <div class="message" id="message">

                </div>
            </form>

            <!-- Footer -->
            <div class="login-footer">
                <p>&copy; 2026 SMP Negeri 1 Pulau Derawan</p>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/register.js') }}"></script>
@endsection
