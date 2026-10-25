// ================= ELEMEN =================
const form = document.getElementById('registerForm');
const usernameInput = document.getElementById('username');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const repasswordInput = document.getElementById('repassword');
const togglePassword = document.getElementById('lihatPW');
const togglePassword2 = document.getElementById('lihatPW2');
const btnRegister = document.getElementById('btnRegister');
const message = document.getElementById('message');
const rememberCheckbox = document.getElementById('remember');

// ================= TOGGLE PASSWORD =================
togglePassword.addEventListener('click', () => {
    const iconElement = togglePassword.querySelector('i');
    if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    iconElement.classList.remove('fa-eye');
    iconElement.classList.add('fa-eye-slash');
    togglePassword.setAttribute('aria-label', 'Sembunyikan password');
    } else {
    passwordInput.type = 'password';
    iconElement.classList.remove('fa-eye-slash');
    iconElement.classList.add('fa-eye');
    togglePassword.setAttribute('aria-label', 'Tampilkan password');
    }
});

togglePassword2.addEventListener('click', () => {
    const iconElement = togglePassword2.querySelector('i');
    if (repasswordInput.type === 'password') {
    repasswordInput.type = 'text';
    iconElement.classList.remove('fa-eye');
    iconElement.classList.add('fa-eye-slash');
    togglePassword2.setAttribute('aria-label', 'Sembunyikan password');
    } else {
    repasswordInput.type = 'password';
    iconElement.classList.remove('fa-eye-slash');
    iconElement.classList.add('fa-eye');
    togglePassword2.setAttribute('aria-label', 'Tampilkan password');
    }
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
  const repassword = repasswordInput.value.trim();
  const emailInput = document.getElementById('email');

  if (!emailInput.value.trim()) {
    showMessage('⚠️ Email wajib diisi!');
    emailInput.focus();
    return false;
  }

  if(emailInput.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
    showMessage('⚠️ Format email tidak valid!');
    emailInput.focus();
    return false;
  }



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

  if(!repassword){
    showMessage('⚠️ Konfirmasi password wajib diisi!');
    repasswordInput.focus();
    return false;
  }

  if (password.length < 6) {
    showMessage('⚠️ Password minimal 6 karakter!');
    passwordInput.focus();
    return false;
  }

  if (password !== repasswordInput.value.trim()) {
    showMessage('⚠️ Password dan konfirmasi password tidak cocok!');
    repasswordInput.focus();
    return false;
  }


  return true;
}

// ================= SUBMIT =================
form.addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!validateForm()) return;

    // Simulasi loading
    btnRegister.classList.add('loading');
    btnRegister.disabled = true;

    // Simulasi proses login (ganti dengan fetch ke API sebenarnya)
    await new Promise((resolve) => setTimeout(resolve, 1500));

    // Simulasi kredensial berhasil
    const username = usernameInput.value.trim();
    const password = passwordInput.value.trim();
    const repassword = repasswordInput.value.trim();
    const email = emailInput.value.trim();

    const res = await fetch('/register/akun', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'accept': 'application/json',
            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
            name: username,
            email: email,
            password: password,
            password_confirmation: repassword
        })
    });

    const data = await res.json();

    if (data.success) {
        showMessage('✅ Registrasi berhasil! ', 'success');


        // Reset animasi tombol
        btnRegister.classList.remove('loading');
        btnRegister.disabled = false;

        setTimeout(() => {
            window.location.href = data.redirect_url;
        }, 1200);
    } else {
        btnRegister.classList.remove('loading');
        btnRegister.disabled = false;
        showMessage('❌ ' + data.message);
        passwordInput.value = '';
        passwordInput.focus();
        passwordInput.type = 'password';
        togglePassword.innerHTML = '<i class="fa-solid fa-eye"></i>';
        repasswordInput.value = '';
        repasswordInput.type = 'password';
        togglePassword2.innerHTML = '<i class="fa-solid fa-eye"></i>';
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
