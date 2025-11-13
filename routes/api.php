<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CoinController;
use App\Http\Controllers\Api\PokemonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Main API Resources
Route::apiResource('/products', ProductController::class);

// Pokemon Routes Group
Route::prefix('pokemon')->group(function () {
    Route::get('/', [PokemonController::class, 'index']);
    Route::get('/{id}', [PokemonController::class, 'show']);
    Route::post('/{id}/favorite', [PokemonController::class, 'toggleFavorite']);
});

// Favorites Routes Group  
Route::prefix('favorites')->group(function () {
    Route::get('/', [PokemonController::class, 'favorites']);
    Route::get('/search', [PokemonController::class, 'searchFavorites']);
    Route::get('/abilities', [PokemonController::class, 'favoriteAbilities']);
    Route::get('/ability/{ability}', [PokemonController::class, 'byAbility']);
});

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'Pokemon API is working!']);
});

// Bonus task route
Route::post('/coins/count', [CoinController::class, 'countCoins']);