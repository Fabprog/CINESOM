<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SpotifyController extends Controller
{
    private function getToken(): string
    {
        return Cache::remember('spotify_token', 3500, function () {
            $response = Http::asForm()->post(config('services.spotify.token_url'), [
                'grant_type'    => 'client_credentials',
                'client_id'     => config('services.spotify.client_id'),
                'client_secret' => config('services.spotify.client_secret'),
            ]);

            if ($response->failed()) {
                abort(502, 'Falha ao autenticar com o Spotify.');
            }

            $token = $response->json('access_token');

            if (!is_string($token) || $token === '') {
                abort(502, 'Falha ao autenticar com o Spotify.');
            }

            return $token;
        });
    }

    public function search(Request $request)
    {
        $request->validate([
            'q'    => 'required|string|max:100',
            'type' => 'sometimes|in:track,artist,album',
        ]);

        $response = Http::withToken($this->getToken())
            ->get(config('services.spotify.api_url') . '/search', [
                'q'     => $request->q,
                'type'  => $request->get('type', 'track'),
                'limit' => 10, // fixo — nunca confiar no cliente
            ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Erro ao buscar no Spotify.'], 502);
        }

        $tracks = collect($response->json('tracks.items', []))->map(fn($t) => [
            'id'       => $t['id'],
            'name'     => $t['name'],
            'artists'  => collect($t['artists'])->map(fn($a) => ['name' => $a['name']])->values(),
            'album'    => [
                'name'   => $t['album']['name'],
                'images' => collect($t['album']['images'])->map(fn($i) => ['url' => $i['url']])->values(),
            ],
            'spotify_url'   => $this->safeSpotifyUrl($t['external_urls']['spotify'] ?? null),
        ]);

        return response()->json(['tracks' => ['items' => $tracks]]);
    }

    private function safeSpotifyUrl(?string $url): ?string
    {
        return ($url && str_starts_with($url, 'https://open.spotify.com/')) ? $url : null;
    }
}
