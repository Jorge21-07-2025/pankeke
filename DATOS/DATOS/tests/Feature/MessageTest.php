<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Pet;
use App\Models\User;
use Tests\TestCase;

class MessageTest extends TestCase
{
    public function test_usuario_puede_enviar_mensaje(): void
    {
        $owner = User::factory()->create();
        $sender = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($sender)->post('/mensajes/' . $pet->id, [
            'message' => 'Hola, me interesa tu mascota',
            'to_user_id' => $owner->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('messages', [
            'from_user_id' => $sender->id,
            'to_user_id' => $owner->id,
            'pet_id' => $pet->id,
        ]);
    }

    public function test_no_puede_enviarse_mensaje_a_si_mismo(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/mensajes/' . $pet->id, [
            'message' => 'Hola',
            'to_user_id' => $user->id,
        ]);

        $response->assertStatus(422);
    }

    public function test_usuario_puede_ver_sus_conversaciones(): void
    {
        $owner = User::factory()->create();
        $sender = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $owner->id]);

        Message::create([
            'from_user_id' => $sender->id,
            'to_user_id' => $owner->id,
            'pet_id' => $pet->id,
            'message' => 'Hola',
        ]);

        $response = $this->actingAs($owner)->get('/mensajes');

        $response->assertStatus(200);
        $response->assertJsonStructure(['conversations', 'unread_count']);
    }

    public function test_usuario_puede_ver_mensajes_de_un_chat(): void
    {
        $owner = User::factory()->create();
        $sender = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $owner->id]);

        Message::create([
            'from_user_id' => $sender->id,
            'to_user_id' => $owner->id,
            'pet_id' => $pet->id,
            'message' => 'Hola, me interesa',
        ]);

        $response = $this->actingAs($owner)->get('/mensajes/chat?user_id=' . $sender->id . '&pet_id=' . $pet->id);

        $response->assertStatus(200);
        $response->assertJsonStructure(['messages']);
    }

    public function test_puede_ver_mensajes_no_leidos(): void
    {
        $owner = User::factory()->create();
        $sender = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $owner->id]);

        Message::create([
            'from_user_id' => $sender->id,
            'to_user_id' => $owner->id,
            'pet_id' => $pet->id,
            'message' => 'Hola',
            'read' => false,
        ]);

        $response = $this->actingAs($owner)->get('/mensajes/no-leidos');

        $response->assertStatus(200);
        $response->assertJson(['unread' => 1]);
    }
}
