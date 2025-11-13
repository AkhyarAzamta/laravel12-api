<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pokemons', function (Blueprint $table) {
            $table->id();
            $table->integer('pokeapi_id')->unique();
            $table->string('name');
            $table->json('types')->nullable();
            $table->json('abilities')->nullable();
            $table->json('stats')->nullable();
            $table->string('sprite')->nullable();
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
            
            $table->index('name');
            $table->index('is_favorite');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pokemons');
    }
};