<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SoundtrackController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'track'  => 'required|string|max:200',
            'artist' => 'required|string|max:200',
        ]);

        $response = Http::withHeaders([
            'x-rapidapi-key'  => config('services.rapidapi.key'),
            'x-rapidapi-host' => config('services.rapidapi.host'),
        ])->post(config('services.rapidapi.url'), [
            'track'  => $request->track,
            'artist' => $request->artist,
        ]);

        if ($response->failed()) {
            return response()->json(['results' => [], 'error' => 'Erro ao buscar trilha sonora.'], 502);
        }

        $results = collect($response->json() ?? [])->map(function ($item) {
            $posterPath = $item['poster_path'] ?? null;
            $poster     = ($posterPath && preg_match('/^\/[a-zA-Z0-9_\-\.]+\.(jpg|png)$/', $posterPath))
                ? 'https://image.tmdb.org/t/p/w500' . $posterPath
                : null;

            $mediaType = in_array($item['media_type'] ?? '', ['movie', 'tv']) ? $item['media_type'] : 'movie';
            $id        = (int) ($item['id'] ?? 0);

            return [
                'id'             => $id,
                'title'          => $item['title'] ?? $item['name'] ?? null,
                'media_type'     => $mediaType,
                'release_date'   => $item['release_date'] ?? null,
                'first_air_date' => $item['first_air_date'] ?? null,
                'poster'         => $poster,
                'vote_average'   => $item['vote_average'] ?? null,
                'tmdb_url'       => 'https://www.themoviedb.org/' . $mediaType . '/' . $id,
            ];
        })->values();

        // Registra IDs retornados na whitelist da sessão para permitir /tmdb/providers
        $newIds  = $results->pluck('id')->filter()->map(fn($id) => (int) $id)->all();
        $allowed = array_values(array_unique(array_merge(session('tmdb_allowed_ids', []), $newIds)));
        session(['tmdb_allowed_ids' => array_slice($allowed, -50)]);

        return response()->json(['results' => $results]);
    }
}
