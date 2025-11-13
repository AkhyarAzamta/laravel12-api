<?php

namespace App\Http\Controllers\Api;

use App\Services\PokeApiService;
use App\Http\Resources\PokemonResource;
use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class PokemonController extends Controller
{
    private PokeApiService $pokeApiService;

    public function __construct(PokeApiService $pokeApiService)
    {
        $this->pokeApiService = $pokeApiService;
    }

    /**
     * GET /api/pokemon
     * Daftar Pokemon dengan pagination + image (sprite)
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'page' => 'sometimes|integer|min:1',
                'limit' => 'sometimes|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'messages' => $validator->errors()
                ], 422);
            }

            $page = (int) $request->get('page', 1);
            $limit = (int) $request->get('limit', 20);

            $pokemons = $this->pokeApiService->getPokemons($page, $limit);

            if (!$pokemons || !isset($pokemons['results'])) {
                return response()->json(['error' => 'Failed to fetch Pokémons from PokeAPI'], 502);
            }

            return response()->json([
                'data' => $pokemons['results'],
                'pagination' => [
                    'current_page' => $page,
                    'total' => $pokemons['count'] ?? count($pokemons['results']),
                    'per_page' => $limit,
                    'last_page' => isset($pokemons['count'])
                        ? ceil($pokemons['count'] / $limit)
                        : 1,
                    'next_page_url' => url("/api/pokemon?page=" . ($page + 1)),
                    'prev_page_url' => $page > 1 ? url("/api/pokemon?page=" . ($page - 1)) : null,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Pokemon index error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/pokemon/{id}
     * Detail Pokemon berdasarkan ID atau nama
     */
    public function show($id)
    {
        try {
            if (!isset($id) || (!is_numeric($id) && !is_string($id))) {
                return response()->json(['error' => 'Invalid Pokémon identifier'], 400);
            }

            $pokemon = $this->pokeApiService->getPokemonDetail($id);

            if (!$pokemon) {
                return response()->json(['error' => 'Pokémon not found'], 404);
            }

            $favorite = Pokemon::where('pokeapi_id', $pokemon['id'])->first();
            $pokemon['is_favorite'] = (bool) $favorite;

            return response()->json(['data' => $pokemon]);
        } catch (\Exception $e) {
            Log::error('Pokemon show error', [
                'id' => $id ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * POST /api/pokemon/{id}/favorite
     * Tambah / hapus Pokemon dari daftar favorit
     */
    public function toggleFavorite(Request $request, $id)
    {
        try {
            if (!isset($id) || (!is_numeric($id) && !is_string($id))) {
                return response()->json(['error' => 'Invalid Pokémon identifier'], 400);
            }

            $pokemonDetail = $this->pokeApiService->getPokemonDetail($id);

            if (!$pokemonDetail) {
                return response()->json(['error' => 'Pokémon not found'], 404);
            }

            $existing = Pokemon::where('pokeapi_id', $pokemonDetail['id'])->first();

            if ($existing) {
                $existing->delete();
                return response()->json([
                    'message' => 'Pokémon removed from favorites',
                    'is_favorite' => false
                ]);
            }

            $pokemon = Pokemon::create([
                'pokeapi_id' => $pokemonDetail['id'],
                'name' => $pokemonDetail['name'],
                'types' => $pokemonDetail['types'],
                'abilities' => $pokemonDetail['abilities'],
                'stats' => $pokemonDetail['stats'],
                'sprite' => $pokemonDetail['sprite'] ?? $pokemonDetail['sprites']['front_default'] ?? null,
                'height' => $pokemonDetail['height'],
                'weight' => $pokemonDetail['weight'],
                'is_favorite' => true
            ]);

            return response()->json([
                'message' => 'Pokémon added to favorites',
                'data' => new PokemonResource($pokemon),
                'is_favorite' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Toggle favorite error', [
                'id' => $id ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * GET /api/favorites
     * Tampilkan semua Pokémon favorit
     */
    public function favorites()
    {
        try {
            $favorites = Pokemon::all();
            return response()->json([
                'data' => PokemonResource::collection($favorites),
                'count' => $favorites->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Favorites error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * GET /api/favorites/search?q=bulbasaur
     * Cari Pokémon favorit berdasarkan nama
     */
    public function searchFavorites(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'q' => 'sometimes|string|max:100|regex:/^[a-zA-Z0-9\s\-]*$/'
            ], [
                'q.max' => 'Search query must not exceed 100 characters.',
                'q.regex' => 'Search query can only contain letters, numbers, spaces, and hyphens.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'messages' => $validator->errors()
                ], 422);
            }

            $query = $request->get('q', '');
            
            // Jika query kosong, kembalikan semua favorites
            if (empty(trim($query))) {
                $favorites = Pokemon::all();
            } else {
                $favorites = Pokemon::where('name', 'like', "%{$query}%")->get();
            }

            return response()->json([
                'data' => PokemonResource::collection($favorites),
                'count' => $favorites->count(),
                'search_query' => $query
            ]);
        } catch (\Exception $e) {
            Log::error('Search favorites error', [
                'query' => $request->get('q'),
                'message' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * GET /api/favorites/abilities
     * Ambil semua ability unik dari Pokémon favorit
     */
    public function favoriteAbilities()
    {
        try {
            $favorites = Pokemon::all();
            $abilities = [];

            foreach ($favorites as $pokemon) {
                if (!empty($pokemon->abilities) && is_array($pokemon->abilities)) {
                    foreach ($pokemon->abilities as $ability) {
                        // Handle both array structures
                        $name = $ability['ability']['name'] ?? $ability['name'] ?? null;
                        if ($name && is_string($name) && !in_array($name, $abilities)) {
                            $abilities[] = $name;
                        }
                    }
                }
            }

            sort($abilities);

            return response()->json([
                'data' => $abilities,
                'count' => count($abilities)
            ]);
        } catch (\Exception $e) {
            Log::error('Favorite abilities error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * GET /api/favorites/ability/{ability}
     * Tampilkan Pokémon favorit berdasarkan ability tertentu
     */
    public function byAbility(Request $request, $ability)
    {
        try {
            // Validasi parameter ability
            $validator = Validator::make(['ability' => $ability], [
                'ability' => 'required|string|min:1|max:50|regex:/^[a-zA-Z0-9\-]+$/'
            ], [
                'ability.required' => 'Ability parameter is required.',
                'ability.string' => 'Ability must be a string.',
                'ability.min' => 'Ability must be at least 1 character.',
                'ability.max' => 'Ability must not exceed 50 characters.',
                'ability.regex' => 'Ability can only contain letters, numbers, and hyphens.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Invalid ability parameter',
                    'messages' => $validator->errors()
                ], 400);
            }

            $favorites = Pokemon::all();
            $filtered = $favorites->filter(function ($pokemon) use ($ability) {
                if (empty($pokemon->abilities) || !is_array($pokemon->abilities)) {
                    return false;
                }

                foreach ($pokemon->abilities as $data) {
                    // Handle both array structures from PokeAPI and our storage
                    $name = $data['ability']['name'] ?? $data['name'] ?? null;
                    if ($name === $ability) {
                        return true;
                    }
                }
                return false;
            });

            return response()->json([
                'data' => PokemonResource::collection($filtered),
                'ability' => $ability,
                'count' => $filtered->count()
            ]);
        } catch (\Exception $e) {
            Log::error('By ability error', [
                'ability' => $ability,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}