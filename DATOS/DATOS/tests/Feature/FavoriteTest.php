<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Pet;
use App\Models\User;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    public function test_usuario_puede_agregar_favorito(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create();

        $response = $this->actingAs($user)->post('/favoritos/' . $pet->id);

        $response->assertStatus(200);
        $response->assertJson(['favorited' => true]);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'pet_id' => $pet->id,
        ]);
    }

    public function test_usuario_puede_quitar_favorito(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create();

        Favorite::create(['user_id' => $user->id, 'pet_id' => $pet->id]);

        $response = $this->actingAs($user)->post('/favoritos/' . $pet->id);

        $response->assertStatus(200);
        $response->assertJson(['favorited' => false]);
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'pet_id' => $pet->id,
        ]);
    }

    public function test_usuario_puede_ver_sus_favoritos(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create();

        Favorite::create(['user_id' => $user->id, 'pet_id' => $pet->id]);

        $response = $this->actingAs($user)->get('/favoritos');

        $response->assertStatus(200);
        $response->assertJsonStructure(['favorites']);
    }
}
