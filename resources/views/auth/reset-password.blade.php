<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova senha — Cinesom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --bg: #0a0a0a; --surface: #111; --border: rgba(255,255,255,0.06); --text: #f0f0f0; --muted: #555; --accent: #1DB954; --error: #e05252; }
        body { background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px 16px; }
        .card { width: 100%; max-width: 400px; padding: 40px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; }
        .brand { font-size: 1.6rem; font-weight: 900; margin-bottom: 28px; }
        .brand span { color: var(--accent); }
        label { display: block; font-size: 0.8rem; font-weight: 500; color: var(--muted); margin-bottom: 6px; letter-spacing: 0.04em; }
        input[type="email"], input[type="password"], input[type="text"] { width: 100%; padding: 14px; background: #0a0a0a; border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-family: 'Inter', sans-serif; font-size: 1rem; outline: none; transition: border-color 0.2s; -webkit-appearance: none; }
        input:focus { border-color: rgba(29,185,84,0.4); }
        .field { margin-bottom: 18px; }
        .input-wrap { position: relative; }
        .input-wrap input { padding-right: 46px; }
        .toggle-pw { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--muted); padding: 4px; touch-action: manipulation; line-height: 0; }
        .toggle-pw:hover { color: var(--text); }
        .error { font-size: 0.78rem; color: var(--error); margin-top: 5px; }
        .hint { font-size: 0.72rem; color: var(--muted); margin-top: 5px; line-height: 1.5; }
        .btn { width: 100%; padding: 15px; background: var(--accent); border: none; border-radius: 10px; color: #000; font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 8px; transition: opacity 0.2s; touch-action: manipulation; }
        .btn:hover { opacity: 0.88; }
        @media (max-width: 480px) {
            body { align-items: flex-start; padding-top: 40px; }
            .card { padding: 28px 20px; border-radius: 12px; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">Cine<span>som</span></div>

        <form method="POST" action="/reset-password">
            @csrf

            {{-- Token e email como campos ocultos — necessários para validação do broker --}}
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="field">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email" maxlength="254">
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password">Nova senha</label>
                <div class="input-wrap">
                    <input type="password" id="password" name="password" required autocomplete="new-password">
                    <button type="button" class="toggle-pw" data-target="password" aria-label="Mostrar senha">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <p class="hint">Mínimo 8 caracteres, letras maiúsculas e minúsculas, números e símbolos.</p>
                @error('password') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Confirmar nova senha</label>
                <div class="input-wrap">
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                    <button type="button" class="toggle-pw" data-target="password_confirmation" aria-label="Mostrar senha">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn">Redefinir senha</button>
        </form>

        <script nonce="{{ request()->attributes->get('csp_nonce') }}">
            const EYE_OPEN = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
            const EYE_OFF  = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;

            document.querySelectorAll('.toggle-pw').forEach(function (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    const input = document.getElementById(toggleBtn.dataset.target);
                    const showing = input.type === 'password';
                    input.type = showing ? 'text' : 'password';
                    toggleBtn.innerHTML = showing ? EYE_OFF : EYE_OPEN;
                    toggleBtn.style.color = showing ? 'var(--accent)' : 'var(--muted)';
                    toggleBtn.setAttribute('aria-label', showing ? 'Ocultar senha' : 'Mostrar senha');
                });
            });
        </script>
    </div>
</body>
</html>
