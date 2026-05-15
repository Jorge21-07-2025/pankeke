<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_muestra_pagina_de_inicio(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_usuario_puede_registrarse(): void
    {
        $response = $this->post('/registrar', [
            'name' => 'Carlos Pérez',
            'email' => 'carlos@ejemplo.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'role' => 'normal',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'carlos@ejemplo.com']);
    }

    public function test_registro_falla_si_faltan_datos(): void
    {
        $response = $this->post('/registrar', [
            'name' => '',
            'email' => 'correo-invalido',
            'password' => '123',
            'password_confirmation' => '456',
            'role' => 'normal',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_usuario_puede_iniciar_sesion(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('123456'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => '123456',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_login_falla_con_credenciales_incorrectas(): void
    {
        User::factory()->create([
            'email' => 'test@ejemplo.com',
            'password' => bcrypt('123456'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@ejemplo.com',
            'password' => 'contraseña-incorrecta',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_dashboard_requiere_inicio_de_sesion(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_usuario_puede_cerrar_sesion(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_usuario_puede_ver_su_perfil(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/perfil');

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }
}
