<?php

namespace Tests\Feature;

use App\Models\AdoptionRequest;
use App\Models\Pet;
use App\Models\User;
use Tests\TestCase;

class AdoptionRequestTest extends TestCase
{
    public function test_usuario_puede_solicitar_adoptar(): void
    {
        $owner = User::factory()->create();
        $adopter = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($adopter)->post('/adoptar/' . $pet->id, [
            'message' => 'Quiero darle un hogar',
            'phone' => '300 123 4567',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('adoption_requests', [
            'user_id' => $adopter->id,
            'pet_id' => $pet->id,
            'status' => 'en_proceso',
        ]);
    }

    public function test_no_puede_solicitar_adoptar_su_propia_mascota(): void
    {
        $owner = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($owner)->post('/adoptar/' . $pet->id);

        $response->assertStatus(422);
    }

    public function test_no_puede_solicitar_adoptar_dos_veces_la_misma(): void
    {
        $owner = User::factory()->create();
        $adopter = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($adopter)->post('/adoptar/' . $pet->id);
        $response = $this->actingAs($adopter)->post('/adoptar/' . $pet->id);

        $response->assertStatus(422);
    }

    public function test_dueno_puede_aprobar_solicitud(): void
    {
        $owner = User::factory()->create();
        $adopter = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $owner->id]);

        $request = AdoptionRequest::create([
            'user_id' => $adopter->id,
            'pet_id' => $pet->id,
            'status' => 'en_proceso',
        ]);

        $response = $this->actingAs($owner)->patch('/solicitudes/' . $request->id, [
            'status' => 'aprobado',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('adoption_requests', ['id' => $request->id, 'status' => 'aprobado']);
    }

    public function test_dueno_puede_rechazar_solicitud(): void
    {
        $owner = User::factory()->create();
        $adopter = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $owner->id]);

        $request = AdoptionRequest::create([
            'user_id' => $adopter->id,
            'pet_id' => $pet->id,
            'status' => 'en_proceso',
        ]);

        $response = $this->actingAs($owner)->patch('/solicitudes/' . $request->id, [
            'status' => 'rechazado',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('adoption_requests', ['id' => $request->id, 'status' => 'rechazado']);
    }

    public function test_otro_usuario_no_puede_aprobar_solicitud(): void
    {
        $owner = User::factory()->create();
        $adopter = User::factory()->create();
        $stranger = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $owner->id]);

        $request = AdoptionRequest::create([
            'user_id' => $adopter->id,
            'pet_id' => $pet->id,
            'status' => 'en_proceso',
        ]);

        $response = $this->actingAs($stranger)->patch('/solicitudes/' . $request->id, [
            'status' => 'aprobado',
        ]);

        $response->assertStatus(403);
    }
}
