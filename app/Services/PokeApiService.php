<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PokeApiService
{
    private $baseUrl = 'https://pokeapi.co/api/v2';
    
    public function getPokemons($page = 1, $limit = 20)
    {
        $offset = ($page - 1) * $limit;
        $cacheKey = "pokemons_page_{$page}_limit_{$limit}";
        
        return Cache::remember($cacheKey, 3600, function () use ($offset, $limit) {
            try {
                $response = Http::timeout(30)->get("{$this->baseUrl}/pokemon", [
                    'offset' => $offset,
                    'limit' => $limit
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $pokemons = [];
                    
                    // Get basic info first, details will be loaded on demand
                    foreach ($data['results'] as $pokemon) {
                        $pokemons[] = [
                            'name' => $pokemon['name'],
                            'url' => $pokemon['url']
                        ];
                    }
                    
                    return [
                        'count' => $data['count'],
                        'results' => $pokemons
                    ];
                }
                
                Log::error('PokeAPI request failed', ['status' => $response->status()]);
                return null;
                
            } catch (\Exception $e) {
                Log::error('PokeAPI error', ['error' => $e->getMessage()]);
                return null;
            }
        });
    }
    
    public function getPokemonDetail($identifier)
    {
        $cacheKey = "pokemon_{$identifier}";
        
        return Cache::remember($cacheKey, 3600, function () use ($identifier) {
            try {
                $response = Http::timeout(30)->get("{$this->baseUrl}/pokemon/{$identifier}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    return [
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'types' => array_map(function($type) {
                            return $type['type']['name'];
                        }, $data['types']),
                        'abilities' => array_map(function($ability) {
                            return [
                                'name' => $ability['ability']['name'],
                                'is_hidden' => $ability['is_hidden']
                            ];
                        }, $data['abilities']),
                        'stats' => array_map(function($stat) {
                            return [
                                'name' => $stat['stat']['name'],
                                'value' => $stat['base_stat']
                            ];
                        }, $data['stats']),
                        'sprite' => $data['sprites']['front_default'],
                        'height' => $data['height'],
                        'weight' => $data['weight']
                    ];
                }
                
                Log::error('PokeAPI detail request failed', ['identifier' => $identifier, 'status' => $response->status()]);
                return null;
                
            } catch (\Exception $e) {
                Log::error('PokeAPI detail error', ['identifier' => $identifier, 'error' => $e->getMessage()]);
                return null;
            }
        });
    }
}
