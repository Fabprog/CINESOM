<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TmdbController extends Controller
{
    private static array $ostKeywords = [
        'soundtrack', 'motion picture', 'original score', 'from the film',
        'from the movie', 'from the netflix', 'ost', 'trilha sonora',
        'music from', 'inspired by',
    ];

    public function providers(Request $request)
    {
        $request->validate([
            'id'   => 'required|integer|min:1|max:9999999',
            'type' => 'required|in:movie,tv',
        ]);

        $id = (int) $request->id;

        // Anti-IDOR: só aceita IDs que foram gerados por buscas legítimas desta sessão
        if (!in_array($id, session('tmdb_allowed_ids', []), true)) {
            return response()->json(['stream' => [], 'rent' => [], 'buy' => []], 403);
        }

        $response = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.api_url') . "/{$request->type}/{$id}/watch/providers");

        if ($response->failed()) {
            return response()->json(['stream' => [], 'rent' => [], 'buy' => []]);
        }

        $br = $response->json('results.BR') ?? [];

        $mapProvider = fn($p) => [
            'provider_name' => $p['provider_name'],
            'logo_url'      => 'https://image.tmdb.org/t/p/original' . $p['logo_path'],
        ];

        return response()->json([
            'stream' => collect($br['flatrate'] ?? [])->map($mapProvider)->values(),
            'rent'   => collect($br['rent']     ?? [])->map($mapProvider)->values(),
            'buy'    => collect($br['buy']      ?? [])->map($mapProvider)->values(),
        ]);
    }

    public function searchByAlbum(Request $request)
    {
        $request->validate(['album' => 'required|string|max:200']);

        $movieName = $this->extractMovieName($request->album);

        if (!$movieName) {
            return response()->json(['movie' => null, 'reason' => 'not_ost']);
        }

        $response = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.api_url') . '/search/multi', [
                'query'    => $movieName,
                'language' => 'pt-BR',
            ]);

        if ($response->failed()) {
            return response()->json(['movie' => null, 'reason' => 'tmdb_error']);
        }

        $movie = collect($response->json('results') ?? [])
            ->whereIn('media_type', ['movie', 'tv'])
            ->first();

        if (!$movie) {
            return response()->json(['movie' => null]);
        }

        $formatted = $this->formatMovie($movie);
        $this->allowTmdbId((int) $movie['id']);

        return response()->json(['movie' => $formatted]);
    }

    private function allowTmdbId(int $id): void
    {
        $allowed   = session('tmdb_allowed_ids', []);
        $allowed[] = $id;
        session(['tmdb_allowed_ids' => array_values(array_unique(array_slice($allowed, -50)))]);
    }

    private function extractMovieName(string $albumName): ?string
    {
        $lower = strtolower($albumName);

        foreach (self::$ostKeywords as $keyword) {
            if (str_contains($lower, $keyword)) {
                $clean = preg_replace('/[\(\[].*?[\)\]]/u', '', $albumName);
                foreach (self::$ostKeywords as $kw) {
                    $clean = preg_replace('/[:\-]?\s*' . preg_quote($kw, '/') . '[s]?/i', '', $clean);
                }
                return trim($clean) ?: null;
            }
        }

        return null;
    }

    private function formatMovie(array $movie): array
    {
        $isTV = ($movie['media_type'] ?? '') === 'tv';

        $posterPath = $movie['poster_path'] ?? null;
        $poster     = ($posterPath && preg_match('/^\/[a-zA-Z0-9_\-\.]+\.(jpg|png)$/', $posterPath))
            ? 'https://image.tmdb.org/t/p/w500' . $posterPath
            : null;

        $mediaType = $movie['media_type'];
        $id        = (int) $movie['id'];

        return [
            'id'         => $id,
            'title'      => $isTV ? ($movie['name'] ?? '') : ($movie['title'] ?? ''),
            'overview'   => $movie['overview'] ?? '',
            'poster'     => $poster,
            'year'       => substr($isTV ? ($movie['first_air_date'] ?? '') : ($movie['release_date'] ?? ''), 0, 4),
            'media_type' => $mediaType,
            'rating'     => $movie['vote_average'] ?? null,
            'tmdb_url'   => 'https://www.themoviedb.org/' . $mediaType . '/' . $id,
        ];
    }
}
