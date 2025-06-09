<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pondok 42 Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: 'Inter', sans-serif;
      background-color: #000;
    }

    .container {
      display: flex;
      height: 100vh;
      width: 100%;
      position: relative; /* Tambahkan supaya posisi absolute anak bekerja */
    }

    .left-side {
      flex: 1;
      background-image: url('/images/lamon.png');
      background-size: cover;
      background-position: center;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      color: #fff;
    }

    .left-top {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .logo {
      font-size: 28px;
      font-weight: 700;
    }

    .nav a {
      color: white;
      text-decoration: none;
      margin-right: 16px;
      font-size: 14px;
      font-weight: 500;
    }

    .welcome {
      font-size: 36px;
      font-weight: 700;
    }

    .right-side {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
      background-image: url('/images/lomon.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      position: relative; /* Supaya gambar tambahan bisa diposisikan di dalam sini */
    }

    .foto-hiasan {
      position: absolute;
      bottom: 20px;
      right: 20px;
      width: 150px;
      height: auto;
      z-index: 2;
    }

    .login-card {
      background-color: #fff;
      border-radius: 32px;
      width: 100%;
      max-width: 400px;
      padding: 40px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .login-card h2 {
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 24px;
    }

    .form-control {
      width: 100%;
      border: none;
      background-color: #e9e9e9;
      border-radius: 24px;
      padding: 12px 16px;
      font-size: 14px;
      margin-bottom: 16px;
    }

    .form-row {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
      color: #555;
      margin-bottom: 16px;
    }

    .btn-login {
      background-color: #111;
      color: #fff;
      border: none;
      width: 100%;
      padding: 12px;
      border-radius: 24px;
      font-weight: 600;
      margin-bottom: 12px;
    }

    .btn-signup {
      background-color: #e9e9e9;
      border: none;
      width: 100%;
      padding: 12px;
      border-radius: 24px;
      font-weight: 500;
    }

    .divider {
      text-align: center;
      font-size: 12px;
      color: #888;
      margin-bottom: 12px;
    }

    .alert-error {
      background-color: #ffe0e0;
      color: #b00020;
      padding: 10px 16px;
      border-radius: 10px;
      font-size: 13px;
      margin-bottom: 16px;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .left-side, .right-side {
        flex: unset;
        width: 100%;
      }

      .left-side {
        min-height: 40vh;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <!-- Kiri -->
  <div class="left-side">
    <div class="left-top">
      <div class="logo">Soto Lamongan Pondok 42</div>
      <div class="nav">
        <a href="#">HOME</a>
        <a href="#">CONTACT</a>
      </div>
    </div>
    <div class="welcome">Selamat Datang!</div>
  </div>

  <!-- Kanan -->
  <div class="right-side">
    <div class="login-card">
      <h2>Log in</h2>

      {{-- Notifikasi error login --}}
      @if (session('error'))
        <div class="alert-error">
          {{ session('error') }}
        </div>
      @endif

      <form method="POST" action="{{ url('/login') }}">
        @csrf
        <input type="email" class="form-control" placeholder="Username" name="email" required>
        <input type="password" class="form-control" placeholder="Password" name="password" required>
        <div class="form-row">
          <label><input type="checkbox"> Remember Me</label>
        </div>
        <button type="submit" class="btn-login">Log in</button>
      </form>
    </div>

  </div>
</div>

</body>
</html>
