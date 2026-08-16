<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muitas tentativas — Cinesom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --bg: #0a0a0a; --surface: #111; --border: rgba(255,255,255,0.06); --text: #f0f0f0; --muted: #555; --accent: #1DB954; }
        body { background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px 16px; }
        .card { width: 100%; max-width: 400px; padding: 40px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; text-align: center; }
        .brand { font-size: 1.6rem; font-weight: 900; margin-bottom: 32px; }
        .brand span { color: var(--accent); }
        .code { font-size: 3.5rem; font-weight: 900; color: var(--accent); line-height: 1; margin-bottom: 12px; }
        .title { font-size: 1rem; font-weight: 600; margin-bottom: 10px; }
        .desc { font-size: 0.82rem; color: var(--muted); line-height: 1.6; margin-bottom: 28px; }
        .btn { display: inline-block; padding: 13px 28px; background: var(--accent); border-radius: 10px; color: #000; font-family: 'Inter', sans-serif; font-size: 0.9rem; font-weight: 700; text-decoration: none; transition: opacity 0.2s; touch-action: manipulation; }
        .btn:hover { opacity: 0.88; }
        @media (max-width: 480px) {
            body { align-items: flex-start; padding-top: 60px; }
            .card { padding: 28px 20px; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">Cine<span>som</span></div>
        <div class="code">429</div>
        <p class="title">Muitas tentativas</p>
        <p class="desc">Você fez requisições demais em pouco tempo.<br>Aguarde um momento e tente novamente.</p>
        <a href="/login" class="btn">Voltar ao login</a>
    </div>
</body>
</html>
