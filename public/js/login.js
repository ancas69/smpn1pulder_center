// ================= ELEMEN =================
const form = document.getElementById('loginForm');
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const btnLogin = document.getElementById('btnLogin');
const message = document.getElementById('message');
const rememberCheckbox = document.getElementById('remember');

// ================= TOGGLE PASSWORD =================
togglePassword.addEventListener('click', () => {
  const isPassword = passwordInput.type === 'password';
  passwordInput.type = isPassword ? 'text' : 'password';
  togglePassword.innerHTML = isPassword
    ? '<i class="fa-solid fa-eye-slash"></i>'
    : '<i class="fa-solid fa-eye"></i>';
  togglePassword.setAttribute(
    'aria-label',
    isPassword ? 'Sembunyikan password' : 'Tampilkan password'
  );
});

// ================= TAMPILKAN PESAN =================
function showMessage(text, type = 'error') {
  message.textContent = text;
  message.className = `message show ${type}`;
  clearTimeout(showMessage.timeout);
  showMessage.timeout = setTimeout(() => {
    message.className = 'message';
  }, 3500);
}

// ================= VALIDASI =================
function validateForm() {
  const username = usernameInput.value.trim();
  const password = passwordInput.value.trim();

  if (!username) {
    showMessage('⚠️ Username / NISN wajib diisi!');
    usernameInput.focus();
    return false;
  }

  if (username.length < 3) {
    showMessage('⚠️ Username minimal 3 karakter!');
    usernameInput.focus();
    return false;
  }

  if (!password) {
    showMessage('⚠️ Password wajib diisi!');
    passwordInput.focus();
    return false;
  }

  if (password.length < 6) {
    showMessage('⚠️ Password minimal 6 karakter!');
    passwordInput.focus();
    return false;
  }

  return true;
}

// ================= SUBMIT =================
form.addEventListener('submit', async (e) => {
  e.preventDefault();

  if (!validateForm()) return;

  // Simulasi loading
  btnLogin.classList.add('loading');
  btnLogin.disabled = true;

  // Simulasi proses login (ganti dengan fetch ke API sebenarnya)
  await new Promise((resolve) => setTimeout(resolve, 1500));

  // Simulasi kredensial berhasil
  const username = usernameInput.value.trim();
  const password = passwordInput.value.trim();

  // 🔑 Ganti logika ini dengan autentikasi server sesungguhnya
  const isDemo = username === 'admin' && password === 'admin123';

  if (isDemo) {
    showMessage('✅ Login berhasil! Mengarahkan...', 'success');

    if (rememberCheckbox.checked) {
      localStorage.setItem('smpn1_derawan_user', username);
    }

    // Reset animasi tombol
    btnLogin.classList.remove('loading');
    btnLogin.disabled = false;

    setTimeout(() => {
      window.location.href = 'dashboard.html'; // halaman tujuan
    }, 1200);
  } else {
    btnLogin.classList.remove('loading');
    btnLogin.disabled = false;
    showMessage('❌ Username atau password salah!');
    passwordInput.value = '';
    passwordInput.focus();
    passwordInput.type = 'password';
    togglePassword.innerHTML = '<i class="fa-solid fa-eye"></i>';
  }
});

// ================= AUTOFILL USERNAME =================
window.addEventListener('DOMContentLoaded', () => {
  const savedUser = localStorage.getItem('smpn1_derawan_user');
  if (savedUser) {
    usernameInput.value = savedUser;
    rememberCheckbox.checked = true;
    passwordInput.focus();
  } else {
    usernameInput.focus();
  }
});

// ================= EFEK INTERAKTIF: SHAKE SAAT ERROR =================
const card = document.querySelector('.login-card');
function shakeCard() {
  card.animate(
    [
      { transform: 'translateX(0)' },
      { transform: 'translateX(-10px)' },
      { transform: 'translateX(10px)' },
      { transform: 'translateX(-8px)' },
      { transform: 'translateX(8px)' },
      { transform: 'translateX(0)' },
    ],
    { duration: 400, easing: 'ease-in-out' }
  );
}

// Override showMessage untuk error agar memicu shake
const originalShow = showMessage;
showMessage = function (text, type = 'error') {
  originalShow(text, type);
  if (type === 'error') shakeCard();
};