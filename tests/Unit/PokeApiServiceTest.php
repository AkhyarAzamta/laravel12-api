<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PokeApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PokeApiServiceTest extends TestCase
{
    private $pokeApiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pokeApiService = new PokeApiService();
    }

    public function test_can_get_pokemons()
    {
        Http::fake([
            'pokeapi.co/api/v2/pokemon*' => Http::response([
                'count' => 100,
                'results' => [
                    ['name' => 'bulbasaur', 'url' => 'https://pokeapi.co/api/v2/pokemon/1/'],
                    ['name' => 'charmander', 'url' => 'https://pokeapi.co/api/v2/pokemon/4/']
                ]
            ], 200)
        ]);

        $result = $this->pokeApiService->getPokemons(1, 2);

        $this->assertIsArray($result);
        $this->assertEquals(100, $result['count']);
        $this->assertCount(2, $result['results']);
    }

    public function test_can_get_pokemon_detail()
    {
        Http::fake([
            'pokeapi.co/api/v2/pokemon/1' => Http::response([
                'id' => 1,
                'name' => 'bulbasaur',
                'types' => [['type' => ['name' => 'grass']]],
                'abilities' => [['ability' => ['name' => 'overgrow'], 'is_hidden' => false]],
                'stats' => [['stat' => ['name' => 'hp'], 'base_stat' => 45]],
                'sprites' => ['front_default' => 'sprite_url'],
                'height' => 7,
                'weight' => 69
            ], 200)
        ]);

        $result = $this->pokeApiService->getPokemonDetail(1);

        $this->assertIsArray($result);
        $this->assertEquals('bulbasaur', $result['name']);
        $this->assertEquals(1, $result['id']);
    }

    public function test_returns_null_on_api_failure()
    {
        Http::fake([
            'pokeapi.co/api/v2/pokemon*' => Http::response([], 500)
        ]);

        $result = $this->pokeApiService->getPokemons(1, 2);

        $this->assertNull($result);
    }

    public function test_uses_cache()
    {
        Http::fake([
            'pokeapi.co/api/v2/pokemon*' => Http::response([
                'count' => 100,
                'results' => [['name' => 'test', 'url' => 'url']]
            ], 200)
        ]);

        // First call - should call API
        $result1 = $this->pokeApiService->getPokemons(1, 1);
        
        // Second call - should use cache
        $result2 = $this->pokeApiService->getPokemons(1, 1);

        $this->assertEquals($result1, $result2);
        
        // Verify API was called only once
        Http::assertSentCount(1);
    }
}