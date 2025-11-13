<?php

namespace Tests\Feature;

use Tests\TestCase;

class CoinControllerTest extends TestCase
{
    public function test_can_count_coins()
    {
        $coins = [50, 1000, 400, 50, 300, 1200, 1000, 25, 50, 45, 100];

        $response = $this->postJson('/api/coins/count', [
            'coins' => $coins
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['coins'])
            ->assertJson([
                'coins' => [
                    '3x 50',
                    '2x 1000', 
                    '1x 400',
                    '1x 300',
                    '1x 1200',
                    '1x 25',
                    '1x 45',
                    '1x 100'
                ]
            ]);
    }

    public function test_validation_for_coins()
    {
        $response = $this->postJson('/api/coins/count', [
            'coins' => 'invalid'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'coins'
                ]
            ]);
    }

    public function test_validation_for_coin_values()
    {
        $response = $this->postJson('/api/coins/count', [
            'coins' => [50, 'invalid', 100]
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ]);
    }
}