<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PokemonResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pokeapi_id' => $this->pokeapi_id,
            'name' => $this->name,
            'types' => $this->types,
            'abilities' => $this->abilities,
            'stats' => $this->stats,
            'sprite' => $this->sprite,
            'height' => $this->height,
            'weight' => $this->weight,
            'is_favorite' => $this->is_favorite,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}