<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cinesom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg: #0a0a0a;
            --surface: #111;
            --surface-hover: #161616;
            --border: rgba(255,255,255,0.06);
            --text: #f0f0f0;
            --muted: #555;
            --accent: #1DB954;
            --accent-dim: rgba(29,185,84,0.12);
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        /* ── HERO ── */
        .hero {
            padding: 80px 40px 60px;
            max-width: 900px;
            margin: 0 auto;
        }

        .hero-label {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 900;
            line-height: 0.95;
            letter-spacing: -0.03em;
            color: var(--text);
            margin-bottom: 28px;
        }

        .hero-title span {
            color: var(--accent);
        }

        .hero-sub {
            font-size: 1rem;
            font-weight: 400;
            color: var(--muted);
            max-width: 420px;
            line-height: 1.6;
        }

        /* ── SEARCH ── */
        .search-wrap {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 40px 60px;
        }

        .search-field {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-field svg {
            position: absolute;
            left: 20px;
            color: var(--muted);
            flex-shrink: 0;
            pointer-events: none;
        }

        .search-field input {
            width: 100%;
            padding: 18px 20px 18px 52px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 400;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-field input:focus {
            border-color: rgba(29,185,84,0.4);
            box-shadow: 0 0 0 3px rgba(29,185,84,0.08);
        }

        .search-field input::placeholder { color: var(--muted); }

        /* ── STATUS ── */
        #message {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 40px 32px;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--muted);
            letter-spacing: 0.02em;
        }

        /* ── RESULTS ── */
        #results {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 40px 80px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        /* ── TRACK CARD ── */
        .track-card {
            border-radius: 12px;
            overflow: hidden;
            transition: background 0.15s;
        }

        .track-card:hover { background: var(--surface-hover); }

        .track-row {
            display: grid;
            grid-template-columns: 52px 1fr auto;
            align-items: center;
            gap: 16px;
            padding: 14px 16px;
            cursor: pointer;
        }

        .track-row img {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .track-info { min-width: 0; }

        .track-name {
            font-size: 0.95rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--text);
        }

        .track-meta {
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .track-meta span { color: #3a3a3a; margin: 0 5px; }

        .open-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--accent-dim);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            flex-shrink: 0;
            font-size: 0.75rem;
            transition: background 0.2s;
        }

        .track-card:hover .open-icon { background: rgba(29,185,84,0.22); }

        /* ── MOVIE ROW ── */
        .movie-row {
            display: grid;
            grid-template-columns: 40px 1fr;
            align-items: center;
            gap: 14px;
            padding: 10px 16px 14px 84px;
            cursor: pointer;
            border-top: 1px solid var(--border);
        }

        .movie-row:hover .movie-title { color: var(--accent); }

        .movie-poster {
            width: 40px;
            height: 60px;
            border-radius: 6px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .no-poster {
            width: 40px;
            height: 60px;
            border-radius: 6px;
            background: var(--surface);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .movie-info { min-width: 0; }

        .movie-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 5px;
        }

        .movie-title {
            font-size: 0.88rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: color 0.15s;
        }

        .movie-meta {
            font-size: 0.74rem;
            color: var(--muted);
            margin-top: 3px;
        }

        .movie-overview {
            font-size: 0.73rem;
            color: #3d3d3d;
            margin-top: 5px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .providers {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-top: 10px;
        }

        .providers-group {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .providers-label {
            font-size: 0.62rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            width: 46px;
            flex-shrink: 0;
        }

        .providers-group img {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            object-fit: cover;
            transition: transform 0.15s;
        }

        .providers-group img:hover { transform: scale(1.15); }

        /* ── LOADING / BUTTON ── */
        .movie-loading {
            padding: 10px 16px 14px 84px;
            border-top: 1px solid var(--border);
            font-size: 0.75rem;
            color: #2a2a2a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .movie-loading::before {
            content: '';
            width: 12px;
            height: 12px;
            border: 2px solid #2a2a2a;
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            flex-shrink: 0;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .soundtrack-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px 14px 84px;
            border-top: 1px solid var(--border);
            background: none;
            border-left: none;
            border-right: none;
            border-bottom: none;
            color: #333;
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            text-align: left;
            transition: color 0.2s;
            letter-spacing: 0.02em;
        }

        .soundtrack-btn:hover { color: var(--accent); }

        /* ── DIVIDER ── */
        .results-divider {
            height: 1px;
            background: var(--border);
            margin: 8px 0;
        }

        @media (max-width: 600px) {
            .hero { padding: 72px 16px 32px; }
            .search-wrap, #message, #results { padding-left: 16px; padding-right: 16px; }
            .search-wrap { padding-bottom: 32px; }
            #results { padding-bottom: 60px; }
            .hero-title { font-size: 2.8rem; }
            .hero-sub { font-size: 0.9rem; }
            .movie-row, .movie-loading, .soundtrack-btn { padding-left: 16px; }
            .track-row { gap: 12px; padding: 12px; }
            .logout-btn { top: 12px; right: 12px; font-size: 0.7rem; padding: 6px 10px; }
        }
    </style>
</head>
<body>

    <div style="position:fixed;top:16px;right:16px;z-index:10">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="logout-btn" style="background:none;border:1px solid rgba(255,255,255,0.08);color:#555;font-family:'Inter',sans-serif;font-size:0.75rem;padding:7px 14px;border-radius:8px;cursor:pointer;transition:color 0.2s,border-color 0.2s;touch-action:manipulation" onmouseover="this.style.color='#f0f0f0';this.style.borderColor='rgba(255,255,255,0.2)'" onmouseout="this.style.color='#555';this.style.borderColor='rgba(255,255,255,0.08)'">Sair</button>
        </form>
    </div>

    <div class="hero">
        <p class="hero-label">Descubra a trilha sonora</p>
        <h1 class="hero-title">Cine<span>som</span></h1>
        <p class="hero-sub">Busque uma música e descubra em qual filme ou série ela apareceu.</p>
    </div>

    <div class="search-wrap">
        <div class="search-field">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" id="query" placeholder="Nome da música, artista..." autocomplete="off" />
        </div>
    </div>

    <p id="message"></p>
    <div id="results"></div>

    <script nonce="{{ request()->attributes->get('csp_nonce', '') }}">
        const OST_KEYWORDS = ['soundtrack','motion picture','original score','from the film','from the movie','from the netflix','ost','trilha sonora','music from','inspired by'];

        function isOst(albumName) {
            return OST_KEYWORDS.some(kw => albumName.toLowerCase().includes(kw));
        }

        function esc(str) {
            const d = document.createElement('div');
            d.textContent = String(str ?? '');
            return d.innerHTML;
        }

        let debounceTimer;

        document.getElementById('query').addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(search, 400);
        });

        async function search() {
            const q = document.getElementById('query').value.trim();
            const message = document.getElementById('message');
            const results = document.getElementById('results');

            if (!q) { results.innerHTML = ''; message.textContent = ''; return; }

            message.textContent = 'Buscando...';
            results.innerHTML = '';

            try {
                const res = await fetch(`/spotify/search?q=${encodeURIComponent(q)}`);
                const data = await res.json();
                const tracks = data?.tracks?.items ?? [];

                if (!tracks.length) { message.textContent = 'Nenhum resultado encontrado.'; return; }

                message.textContent = '';

                tracks.forEach(track => {
                    const artists = track.artists.map(a => a.name).join(', ');
                    const image   = track.album.images[1]?.url ?? track.album.images[0]?.url ?? '';
                    const url     = track.spotify_url ?? null;
                    const cardId  = `card-${esc(track.id)}`;
                    const ost     = isOst(track.album.name);

                    const card = document.createElement('div');
                    card.className = 'track-card';

                    const row = document.createElement('div');
                    row.className = 'track-row';
                    row.addEventListener('click', () => { if (url) window.open(url, '_blank', 'noopener,noreferrer'); });

                    const img = document.createElement('img');
                    img.src = image;
                    img.alt = '';

                    const info = document.createElement('div');
                    info.className = 'track-info';

                    const name = document.createElement('div');
                    name.className = 'track-name';
                    name.textContent = track.name;

                    const meta = document.createElement('div');
                    meta.className = 'track-meta';
                    meta.textContent = `${artists} · ${track.album.name}`;

                    const icon = document.createElement('div');
                    icon.className = 'open-icon';
                    icon.textContent = '↗';

                    info.append(name, meta);
                    row.append(img, info, icon);
                    card.appendChild(row);

                    const slot = document.createElement('div');
                    slot.id = cardId;

                    if (ost) {
                        slot.className = 'movie-loading';
                        slot.textContent = 'Verificando trilha sonora...';
                    } else {
                        const btn = document.createElement('button');
                        btn.className = 'soundtrack-btn';
                        btn.textContent = '🎬 Ver em qual filme ou série aparece';
                        btn.addEventListener('click', () => fetchSoundtrack(btn, track.name, track.artists[0]?.name ?? ''));
                        slot.appendChild(btn);
                    }

                    card.appendChild(slot);
                    results.appendChild(card);

                    if (ost) fetchMovie(track.album.name, cardId);
                });

            } catch (e) {
                message.textContent = 'Erro ao buscar. Tente novamente.';
            }
        }

        async function fetchProviders(tmdbId, mediaType, container) {
            try {
                const type = mediaType === 'tv' ? 'tv' : 'movie';
                const res  = await fetch(`/tmdb/providers?id=${encodeURIComponent(tmdbId)}&type=${encodeURIComponent(type)}`);
                const data = await res.json();

                const el = typeof container === 'string' ? document.getElementById(container) : container;
                if (!el) return;

                const categories = [
                    { key: 'stream', label: 'Stream' },
                    { key: 'rent',   label: 'Alugar' },
                    { key: 'buy',    label: 'Comprar' },
                ];

                categories.filter(c => data[c.key]?.length).forEach(c => {
                    const group = document.createElement('div');
                    group.className = 'providers-group';

                    const label = document.createElement('span');
                    label.className = 'providers-label';
                    label.textContent = c.label;
                    group.appendChild(label);

                    data[c.key].forEach(p => {
                        const img = document.createElement('img');
                        img.src   = p.logo_url;
                        img.title = p.provider_name;
                        group.appendChild(img);
                    });

                    el.appendChild(group);
                });
            } catch (e) {}
        }

        async function fetchMovie(albumName, cardId) {
            const slot = document.getElementById(cardId);
            if (!slot) return;

            try {
                const res   = await fetch(`/tmdb/search?album=${encodeURIComponent(albumName)}`);
                const data  = await res.json();
                const movie = data?.movie;

                if (!movie) { slot.remove(); return; }

                const type   = movie.media_type === 'tv' ? 'Série' : 'Filme';
                const year   = movie.year ? ` · ${movie.year}` : '';
                const rating = movie.rating ? ` · ★ ${parseFloat(movie.rating).toFixed(1)}` : '';

                const row = document.createElement('div');
                row.className = 'movie-row';
                row.addEventListener('click', () => { if (movie.tmdb_url) window.open(movie.tmdb_url, '_blank', 'noopener,noreferrer'); });

                const posterEl = movie.poster
                    ? Object.assign(document.createElement('img'), { className: 'movie-poster', src: movie.poster, alt: movie.title })
                    : Object.assign(document.createElement('div'), { className: 'no-poster', textContent: '🎬' });

                const info = document.createElement('div');
                info.className = 'movie-info';

                const lbl = document.createElement('div'); lbl.className = 'movie-label'; lbl.textContent = 'Aparece em';
                const ttl = document.createElement('div'); ttl.className = 'movie-title'; ttl.textContent = movie.title;
                const mt  = document.createElement('div'); mt.className  = 'movie-meta';  mt.textContent  = `${type}${year}${rating}`;
                const ov  = document.createElement('div'); ov.className  = 'movie-overview'; ov.textContent = movie.overview;
                const prov = document.createElement('div'); prov.className = 'providers';

                info.append(lbl, ttl, mt, ov, prov);
                row.append(posterEl, info);
                slot.replaceWith(row);

                fetchProviders(movie.id, movie.media_type, prov);
            } catch (e) {
                if (document.getElementById(cardId)) document.getElementById(cardId).remove();
            }
        }

        async function fetchSoundtrack(btn, trackName, artistName) {
            btn.disabled = true;
            btn.textContent = 'Buscando...';

            try {
                const res = await fetch('/soundtrack/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
                    },
                    body: JSON.stringify({ track: trackName, artist: artistName }),
                });

                if (res.status === 429) {
                    const data = await res.json();
                    btn.textContent = data.error ?? 'Limite atingido. Tente amanhã.';
                    return;
                }

                const data    = await res.json();
                const results = data?.results ?? [];

                if (!results.length) { btn.textContent = 'Nenhum filme encontrado para esta música.'; return; }

                const item   = results[0];
                const title  = item.title ?? item.name ?? 'Desconhecido';
                const year   = (item.release_date ?? item.first_air_date ?? '').substring(0, 4);
                const type   = item.media_type === 'tv' ? 'Série' : 'Filme';
                const rating = item.vote_average ? ` · ★ ${parseFloat(item.vote_average).toFixed(1)}` : '';

                const row = document.createElement('div');
                row.className = 'movie-row';
                row.addEventListener('click', () => { if (item.tmdb_url) window.open(item.tmdb_url, '_blank', 'noopener,noreferrer'); });

                const posterEl = item.poster
                    ? Object.assign(document.createElement('img'), { className: 'movie-poster', src: item.poster, alt: '' })
                    : Object.assign(document.createElement('div'), { className: 'no-poster', textContent: '🎬' });

                const info = document.createElement('div');
                info.className = 'movie-info';

                const lbl  = document.createElement('div'); lbl.className  = 'movie-label'; lbl.textContent  = 'Aparece em';
                const ttl  = document.createElement('div'); ttl.className  = 'movie-title'; ttl.textContent  = title;
                const mt   = document.createElement('div'); mt.className   = 'movie-meta';  mt.textContent   = `${type}${year ? ' · ' + year : ''}${rating}`;
                const prov = document.createElement('div'); prov.className = 'providers';

                info.append(lbl, ttl, mt, prov);
                row.append(posterEl, info);
                btn.replaceWith(row);

                fetchProviders(item.id, item.media_type ?? 'movie', prov);
            } catch (e) {
                btn.textContent = 'Erro ao buscar. Tente novamente.';
                btn.disabled = false;
            }
        }
    </script>

</body>
</html>
