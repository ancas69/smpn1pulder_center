<!DOCTYPE html>
<html>

<head>
    <title>Buka Blokir Akun</title>
</head>

<body>
    <h2>Halo, {{ $user->name }}</h2>
    <p>Kami mendeteksi terlalu banyak percobaan login gagal pada akun Anda. Untuk keamanan, akun Anda telah diblokir
        sementara.</p>
    <p>Klik tombol di bawah ini untuk membuka blokir akun Anda:</p>
    <a href="{{ $url }}"
        style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Buka
        Blokir Akun</a>
    <p>Link ini akan kedaluwarsa dalam 1 jam.</p>
    <p>Jika Anda tidak merasa melakukan percobaan login, abaikan email ini.</p>
</body>

</html>
