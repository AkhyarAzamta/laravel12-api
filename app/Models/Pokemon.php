<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Pokemon extends Model
{
    use HasFactory;

    protected $table = 'pokemons';
    
    protected $fillable = [
        'pokeapi_id',
        'name',
        'types',
        'abilities',
        'stats',
        'sprite',
        'height',
        'weight',
        'is_favorite'
    ];
    
    protected $casts = [
        'types' => 'array',
        'abilities' => 'array',
        'stats' => 'array',
        'is_favorite' => 'boolean',
        'height' => 'integer',
        'weight' => 'integer'
    ];

    /**
     * Accessor untuk memastikan abilities selalu array
     */
    public function getAbilitiesAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        
        return [];
    }
    /**
     * Scope untuk filter by ability
     */
    public function scopeWithAbility(Builder $query, $abilityName)
    {
        return $query->where(function ($q) use ($abilityName) {
            // Untuk MySQL dengan JSON support
            $q->whereRaw(
                'JSON_CONTAINS(abilities, ?, "$")',
                [json_encode(['name' => $abilityName])]
            );
        });
    }
}