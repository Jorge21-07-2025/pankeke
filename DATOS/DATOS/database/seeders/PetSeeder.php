<?php

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\Shelter;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        $shelters = Shelter::pluck('id', 'name');

        $pets = [
            [
                'name' => 'Max', 'species' => 'Perro', 'breed' => 'Mestizo',
                'age' => 2, 'age_unit' => 'años', 'gender' => 'Macho',
                'city' => 'Medellín', 'weight' => '15 kg', 'size' => 'Mediano',
                'shelter_id' => $shelters['La Perla'] ?? null,
                'status' => 'Disponible',
                'description' => 'Max es un perro muy cariñoso y juguetón. Le encanta jugar con otros perros y es excelente con niños. Está buscando una familia que le brinde amor y cuidados.',
                'image' => '', 'emoji' => '🐕', 'color' => '#ffeaa7',
            ],
            [
                'name' => 'Luna', 'species' => 'Perro', 'breed' => 'Poodle',
                'age' => 1, 'age_unit' => 'año', 'gender' => 'Hembra',
                'city' => 'Bello', 'weight' => '8 kg', 'size' => 'Pequeño',
                'shelter_id' => $shelters['Patitas Felices'] ?? null,
                'status' => 'Disponible',
                'description' => 'Luna es una perrita muy inteligente y enérgica. Le encanta aprender trucos nuevos y es muy obediente. Necesita una familia activa que pueda darle mucho ejercicio.',
                'image' => 'https://images.unsplash.com/photo-1537151608828-ea2b11777ee8?w=400&h=400&fit=crop',
                'emoji' => '🐩', 'color' => '#dfe6e9',
            ],
            [
                'name' => 'Milo', 'species' => 'Gato', 'breed' => 'Naranja Tabby',
                'age' => 6, 'age_unit' => 'meses', 'gender' => 'Macho',
                'city' => 'Envigado', 'weight' => '3 kg', 'size' => 'Pequeño',
                'shelter_id' => $shelters['Refugio Michis'] ?? null,
                'status' => 'Disponible',
                'description' => 'Milo es un gatito juguetón y curioso. Le encanta explorar y trepar. Es perfecto para una familia que busca un compañero felino lleno de energía.',
                'image' => 'https://images.unsplash.com/photo-1574158622682-e40e69881006?w=400&h=400&fit=crop',
                'emoji' => '🐈', 'color' => '#f59375',
            ],
            [
                'name' => 'Coco', 'species' => 'Gato', 'breed' => 'Siamés',
                'age' => 3, 'age_unit' => 'años', 'gender' => 'Hembra',
                'city' => 'Itagüí', 'weight' => '4 kg', 'size' => 'Pequeño',
                'shelter_id' => $shelters['Gatitos Felices'] ?? null,
                'status' => 'Disponible',
                'description' => 'Coco es una gata independiente y elegante. Prefiere la tranquilidad y es perfecta para personas que buscan una compañera calmada y sofisticada.',
                'image' => 'https://images.unsplash.com/photo-1513360371669-4adf3dd7dff8?w=400&h=400&fit=crop',
                'emoji' => '🐱', 'color' => '#f6a5b8',
            ],
            [
                'name' => 'Rocky', 'species' => 'Perro', 'breed' => 'Labrador',
                'age' => 4, 'age_unit' => 'años', 'gender' => 'Macho',
                'city' => 'Medellín', 'weight' => '28 kg', 'size' => 'Grande',
                'shelter_id' => $shelters['La Perla'] ?? null,
                'status' => 'Disponible',
                'description' => 'Rocky es un perro grande, fuerte y muy leal. Es un excelente compañero para actividades al aire libre y protector con su familia.',
                'image' => 'https://images.unsplash.com/photo-1552053831-71594a27632d?w=400&h=400&fit=crop',
                'emoji' => '🦮', 'color' => '#fdcb6e',
            ],
            [
                'name' => 'Bella', 'species' => 'Gato', 'breed' => 'Persa',
                'age' => 2, 'age_unit' => 'años', 'gender' => 'Hembra',
                'city' => 'Sabaneta', 'weight' => '5 kg', 'size' => 'Mediano',
                'shelter_id' => $shelters['Refugio Michis'] ?? null,
                'status' => 'Disponible',
                'description' => 'Bella es una gata de pelo largo muy dulce y cariñosa. Le encanta recibir mimos y es perfecta para una familia que busca una compañera amorosa.',
                'image' => 'https://images.unsplash.com/photo-1533743983669-94fa5c4338ec?w=400&h=400&fit=crop',
                'emoji' => '😺', 'color' => '#a794d4',
            ],
            [
                'name' => 'Toby', 'species' => 'Perro', 'breed' => 'Labrador',
                'age' => 2, 'age_unit' => 'años', 'gender' => 'Macho',
                'city' => 'Sabaneta', 'weight' => '22 kg', 'size' => 'Grande',
                'shelter_id' => $shelters['La Perla'] ?? null,
                'status' => 'Disponible',
                'description' => 'Toby es un perro labrador muy energético y juguetón. Le encanta correr, jugar a buscar la pelota y nadar. Es ideal para una familia activa que disfrute del aire libre.',
                'image' => 'https://images.unsplash.com/photo-1552053831-71594a27632d?w=400&h=400&fit=crop',
                'emoji' => '🦮', 'color' => '#fdcb6e',
            ],
        ];

        foreach ($pets as $pet) {
            Pet::create($pet);
        }
    }
}
