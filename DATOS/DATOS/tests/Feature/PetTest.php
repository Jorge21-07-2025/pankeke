<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use Tests\TestCase;

class PetTest extends TestCase
{
    public function test_lista_mascotas_requiere_autenticacion(): void
    {
        $response = $this->get('/mascotas/json');

        $response->assertRedirect('/login');
    }

    public function test_usuario_puede_ver_mascotas_disponibles(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/mascotas/json');

        $response->assertStatus(200);
        $response->assertJsonStructure(['pets']);
    }

    public function test_usuario_puede_publicar_una_mascota(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/mascotas', [
            'name' => 'Max',
            'species' => 'Perro',
            'breed' => 'Labrador',
            'age' => 2,
            'age_unit' => 'años',
            'gender' => 'Macho',
            'city' => 'Medellín',
            'size' => 'Grande',
            'weight' => 25,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('pets', ['name' => 'Max', 'user_id' => $user->id]);
    }

    public function test_publicar_mascota_falla_sin_datos_obligatorios(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/mascotas', [
            'name' => '',
            'species' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'species', 'breed', 'age', 'gender', 'city', 'size']);
    }

    public function test_usuario_puede_ver_detalle_de_mascota(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/mascota/' . $pet->id);

        $response->assertStatus(200);
        $response->assertSee($pet->name);
    }

    public function test_usuario_puede_editar_su_mascota(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put('/mascotas/' . $pet->id, [
            'name' => 'Max Modificado',
            'species' => 'Perro',
            'breed' => 'Labrador',
            'age' => 3,
            'age_unit' => 'años',
            'gender' => 'Macho',
            'city' => 'Bogotá',
            'size' => 'Mediano',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('pets', ['id' => $pet->id, 'name' => 'Max Modificado']);
    }

    public function test_usuario_no_puede_editar_mascota_de_otro(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($other)->put('/mascotas/' . $pet->id, [
            'name' => 'Nuevo Nombre',
            'species' => 'Perro',
            'breed' => 'Labrador',
            'age' => 3,
            'age_unit' => 'años',
            'gender' => 'Macho',
            'city' => 'Cali',
            'size' => 'Pequeño',
        ]);

        $response->assertStatus(403);
    }

    public function test_usuario_puede_eliminar_su_mascota(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete('/mascotas/' . $pet->id);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
    }

    public function test_usuario_no_puede_eliminar_mascota_de_otro(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($other)->delete('/mascotas/' . $pet->id);

        $response->assertStatus(403);
    }
}
