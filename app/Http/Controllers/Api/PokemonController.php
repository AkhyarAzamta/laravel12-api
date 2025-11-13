<?php

namespace App\Http\Controllers\Api;

use App\Services\PokeApiService;
use App\Http\Resources\PokemonResource;
use App\Http\Resources\PokemonListResource;
use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class PokemonController extends Controller
{
    private $pokeApiService;

    public function __construct(PokeApiService $pokeApiService)
    {
        $this->pokeApiService = $pokeApiService;
    }

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

            $page = $request->get('page', 1);
            $limit = $request->get('limit', 20);

            $pokemons = $this->pokeApiService->getPokemons($page, $limit);

            if (!$pokemons) {
                return response()->json(['error' => 'Failed to fetch pokemons from PokeAPI'], 500);
            }

            return response()->json([
                'data' => $pokemons['results'],
                'pagination' => [
                    'current_page' => (int) $page,
                    'total' => $pokemons['count'],
                    'per_page' => (int) $limit,
                    'last_page' => ceil($pokemons['count'] / $limit)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Pokemon index error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function show($id)
    {
        try {
            // Validate ID
            if (!isset($id) || (!is_numeric($id) && !is_string($id))) {
                return response()->json(['error' => 'Invalid Pokemon identifier'], 400);
            }

            $pokemon = $this->pokeApiService->getPokemonDetail($id);

            if (!$pokemon) {
                return response()->json(['error' => 'Pokemon not found'], 404);
            }

            // Check if pokemon is in favorites
            $favorite = Pokemon::where('pokeapi_id', $pokemon['id'])->first();
            $pokemon['is_favorite'] = $favorite ? true : false;

            return response()->json(['data' => $pokemon]);

        } catch (\Exception $e) {
            Log::error('Pokemon show error', ['id' => $id ?? null, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function toggleFavorite(Request $request, $id)
    {
        try {
            // Validate ID
            if (!isset($id) || (!is_numeric($id) && !is_string($id))) {
                return response()->json(['error' => 'Invalid Pokemon identifier'], 400);
            }

            $pokemonDetail = $this->pokeApiService->getPokemonDetail($id);

            if (!$pokemonDetail) {
                return response()->json(['error' => 'Pokemon not found'], 404);
            }

            $pokemon = Pokemon::where('pokeapi_id', $pokemonDetail['id'])->first();

            if ($pokemon) {
                $pokemon->delete();
                return response()->json([
                    'message' => 'Pokemon removed from favorites',
                    'is_favorite' => false
                ]);
            }

            $pokemon = Pokemon::create([
                'pokeapi_id' => $pokemonDetail['id'],
                'name' => $pokemonDetail['name'],
                'types' => $pokemonDetail['types'],
                'abilities' => $pokemonDetail['abilities'],
                'stats' => $pokemonDetail['stats'],
                'sprite' => $pokemonDetail['sprite'],
                'height' => $pokemonDetail['height'],
                'weight' => $pokemonDetail['weight'],
                'is_favorite' => true
            ]);

            return response()->json([
                'message' => 'Pokemon added to favorites',
                'data' => new PokemonResource($pokemon),
                'is_favorite' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Toggle favorite error', ['id' => $id ?? null, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function favorites(Request $request)
    {
        try {
            $favorites = Pokemon::all();
            return response()->json([
                'data' => PokemonResource::collection($favorites),
                'count' => $favorites->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Favorites error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function searchFavorites(Request $request)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'q' => 'sometimes|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'messages' => $validator->errors()
                ], 422);
            }

            $query = request()->get('q', '');

            if (empty($query)) {
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
            Log::error('Search favorites error', ['query' => request()->get('q', null), 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function favoriteAbilities()
    {
        try {
            $favorites = Pokemon::all();
            $abilities = [];

            foreach ($favorites as $pokemon) {
                foreach ($pokemon->abilities as $ability) {
                    $abilityName = $ability['name'];
                    if (!in_array($abilityName, $abilities)) {
                        $abilities[] = $abilityName;
                    }
                }
            }

            sort($abilities); // Sort alphabetically

            return response()->json([
                'data' => $abilities,
                'count' => count($abilities)
            ]);
        } catch (\Exception $e) {
            Log::error('Favorite abilities error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function byAbility(Request $request, $ability = null)
    {
        try {
            // Ensure $ability is obtained from either the route param or the request if not provided
            $ability = $ability ?? request()->route('ability') ?? request()->get('ability');

            if (!is_string($ability) || empty($ability)) {
                return response()->json(['error' => 'Invalid ability parameter'], 400);
            }

            // Method yang lebih kompatibel - gunakan collection filtering
            $allFavorites = Pokemon::all();

            $filteredFavorites = $allFavorites->filter(function ($pokemon) use ($ability) {
                if (empty($pokemon->abilities)) {
                    return false;
                }

                // Karena abilities sudah di-cast sebagai array di model, langsung gunakan
                $abilities = $pokemon->abilities;

                if (!is_array($abilities)) {
                    return false;
                }

                // Cari ability yang match
                foreach ($abilities as $abilityData) {
                    if (isset($abilityData['name']) && $abilityData['name'] === $ability) {
                        return true;
                    }
                }

                return false;
            });

            return response()->json([
                'data' => PokemonResource::collection($filteredFavorites),
                'ability' => $ability,
                'count' => $filteredFavorites->count()
            ]);
        } catch (\Exception $e) {
            Log::error('By ability error', [
                'ability' => $ability ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal server error: ' . $e->getMessage()], 500);
        }
    }
}