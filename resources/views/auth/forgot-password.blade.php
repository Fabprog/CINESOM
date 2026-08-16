<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha — Cinesom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --bg: #0a0a0a; --surface: #111; --border: rgba(255,255,255,0.06); --text: #f0f0f0; --muted: #555; --accent: #1DB954; --error: #e05252; }
        body { background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px 16px; }
        .card { width: 100%; max-width: 400px; padding: 40px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; }
        .brand { font-size: 1.6rem; font-weight: 900; margin-bottom: 8px; }
        .brand span { color: var(--accent); }
        .subtitle { font-size: 0.82rem; color: var(--muted); margin-bottom: 28px; line-height: 1.5; }
        label { display: block; font-size: 0.8rem; font-weight: 500; color: var(--muted); margin-bottom: 6px; letter-spacing: 0.04em; }
        input { width: 100%; padding: 14px; background: #0a0a0a; border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-family: 'Inter', sans-serif; font-size: 1rem; outline: none; transition: border-color 0.2s; -webkit-appearance: none; }
        input:focus { border-color: rgba(29,185,84,0.4); }
        .field { margin-bottom: 18px; }
        .error { font-size: 0.78rem; color: var(--error); margin-top: 5px; }
        .status { font-size: 0.82rem; color: var(--accent); margin-bottom: 16px; }
        .btn { width: 100%; padding: 15px; background: var(--accent); border: none; border-radius: 10px; color: #000; font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 8px; transition: opacity 0.2s; touch-action: manipulation; }
        .btn:hover { opacity: 0.88; }
        .links { margin-top: 20px; text-align: center; font-size: 0.8rem; color: var(--muted); }
        .links a { color: var(--accent); text-decoration: none; }
        .links a:hover { text-decoration: underline; }
        @media (max-width: 480px) {
            body { align-items: flex-start; padding-top: 40px; }
            .card { padding: 28px 20px; border-radius: 12px; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">Cine<span>som</span></div>
        <p class="subtitle">Informe seu e-mail e enviaremos um link para redefinir sua senha.</p>

        @if (session('status'))
            <p class="status">{{ session('status') }}</p>
        @endif

        <form method="POST" action="/forgot-password">
            @csrf

            <div class="field">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" maxlength="254">
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn">Enviar link</button>
        </form>

        <div class="links">
            <a href="/login">Voltar ao login</a>
        </div>
    </div>
</body>
</html>
