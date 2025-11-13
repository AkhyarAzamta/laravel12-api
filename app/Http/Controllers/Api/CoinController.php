<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CoinController extends Controller
{
    public function countCoins(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coins' => 'required|array',
            'coins.*' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $coins = $request->input('coins', []);
        
        $counts = array_count_values($coins);
        arsort($counts);
        
        $result = [];
        foreach ($counts as $coin => $count) {
            $result[] = "{$count}x {$coin}";
        }
        
        return response()->json(['coins' => $result]);
    }
}