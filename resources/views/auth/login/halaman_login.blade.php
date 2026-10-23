<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - SMP Negeri 1 Pulau Derawan</title>
  <link rel="stylesheet" href="style.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />
</head>
<body>
  <!-- Background animasi -->
  <div class="bg-animation">
    <span></span><span></span><span></span><span></span><span></span>
  </div>

  <div class="login-container">
    <div class="login-card">
      <!-- Logo & Judul -->
      <div class="login-header">
        <div class="logo">
          <i class="fa-solid fa-school"></i>
        </div>
        <h1>SMP Negeri 1 Pulau Derawan</h1>
        <p>Silakan masuk ke akun Anda</p>
      </div>

      <!-- Form -->
      <form id="loginForm" novalidate>
        <!-- Username -->
        <div class="input-group">
          <i class="fa-solid fa-user input-icon"></i>
          <input type="text" id="username" placeholder=" " autocomplete="username" required />
          <label for="username">Username / NISN</label>
        </div>

        <!-- Password -->
        <div class="input-group">
          <i class="fa-solid fa-lock input-icon"></i>
          <input type="password" id="password" placeholder=" " autocomplete="current-password" required />
          <label for="password">Password</label>
          <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password">
            <i class="fa-solid fa-eye"></i>
          </button>
        </div>

        <!-- Opsi -->
        <div class="form-options">
          <label class="remember">
            <input type="checkbox" id="remember" />
            <span>Ingat saya</span>
          </label>
          <a href="#" class="forgot">Lupa password?</a>
        </div>

        <!-- Tombol -->
        <button type="submit" class="btn-login" id="btnLogin">
          <span class="btn-text">Masuk</span>
          <i class="fa-solid fa-arrow-right btn-icon"></i>
        </button>

        <!-- Pesan error -->
        <div class="message" id="message"></div>
      </form>

      <!-- Footer -->
      <div class="login-footer">
        <p>&copy; 2024 SMP Negeri 1 Pulau Derawan</p>
      </div>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>