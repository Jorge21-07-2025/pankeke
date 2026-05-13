<?php

namespace Database\Seeders;

use App\Models\Shelter;
use Illuminate\Database\Seeder;

class ShelterSeeder extends Seeder
{
    public function run(): void
    {
        $shelters = [
            ['name' => 'La Perla', 'city' => 'Medellín', 'description' => 'Refugio dedicado a la recuperación y adopción de perros callejeros.'],
            ['name' => 'Patitas Felices', 'city' => 'Bello', 'description' => 'Centro de rescate animal comprometido con el bienestar de perros y gatos.'],
            ['name' => 'Refugio Michis', 'city' => 'Envigado', 'description' => 'Refugio especializado en la protección y cuidado de gatos abandonados.'],
            ['name' => 'Gatitos Felices', 'city' => 'Itagüí', 'description' => 'Asociación sin ánimo de lucro dedicada al rescate de felinos.'],
        ];

        foreach ($shelters as $shelter) {
            Shelter::create($shelter);
        }
    }
}
