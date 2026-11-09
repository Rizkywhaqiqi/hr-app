<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk • HR Internal</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #0f2b46, #163a5e);
            padding: 20px;
        }
        .card {
            width: 100%; max-width: 380px;
            background: #fff; border-radius: 14px;
            padding: 30px 28px;
            box-shadow: 0 18px 45px rgba(0,0,0,.35);
        }
        .logo { font-size: 22px; font-weight: 800; color: #0f2b46; text-align: center; letter-spacing: .5px; }
        .logo span { color: #f2711c; }
        .sub { text-align: center; color: #6c7a8a; font-size: 12px; margin: 4px 0 22px; letter-spacing: .5px; }
        label { display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px; color: #22303f; }
        input[type=email], input[type=password] {
            width: 100%; padding: 11px 12px; margin-bottom: 14px;
            border: 1px solid #cfd8e3; border-radius: 8px; font-size: 14px;
        }
        input:focus { outline: 2px solid #f2711c; border-color: #f2711c; }
        .remember { display: flex; align-items: center; gap: 8px; font-weight: 400; font-size: 13px; color: #6c7a8a; margin-bottom: 16px; }
        .remember input { width: auto; margin: 0; }
        button {
            width: 100%; padding: 11px; border: 0; border-radius: 8px;
            background: #f2711c; color: #fff; font-size: 15px; font-weight: 700; cursor: pointer;
        }
        button:hover { background: #d95f0e; }
        .alert { padding: 10px 12px; border-radius: 8px; font-size: 13px; margin-bottom: 14px; }
        .alert-error { background: #fdeaea; color: #a11d1d; border: 1px solid #f3c1c1; }
        .alert-success { background: #e7f7ea; color: #17642a; border: 1px solid #b7e5c0; }
        .hint { margin-top: 16px; text-align: center; font-size: 11.5px; color: #9aa5b1; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">HR <span>Internal</span></div>
        <div class="sub">JOJO GROUP MANAGEMENT</div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@jojo.test" autofocus autocomplete="username">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password">

            <label class="remember">
                <input type="checkbox" name="remember" value="1"> Ingat saya
            </label>

            <button type="submit">Masuk</button>
        </form>

        <div class="hint">Akun demo: admin@jojo.test / password</div>
    </div>
</body>
</html>