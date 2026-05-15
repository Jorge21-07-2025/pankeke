<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    protected static $dogs = [
        ['name' => 'Luna',  'breed' => 'Labrador',      'color' => '#f5d79e', 'emoji' => '🐕', 'weight' => 28, 'size' => 'Grande'],
        ['name' => 'Max',   'breed' => 'Pastor Alemán', 'color' => '#c4956a', 'emoji' => '🐕', 'weight' => 32, 'size' => 'Grande'],
        ['name' => 'Coco',  'breed' => 'Poodle',        'color' => '#f0e6d3', 'emoji' => '🐕', 'weight' => 6,  'size' => 'Pequeño'],
        ['name' => 'Simba', 'breed' => 'Cruce',         'color' => '#e8c87a', 'emoji' => '🐕', 'weight' => 15, 'size' => 'Mediano'],
        ['name' => 'Lola',  'breed' => 'Beagle',        'color' => '#d4a373', 'emoji' => '🐕', 'weight' => 12, 'size' => 'Mediano'],
        ['name' => 'Toby',  'breed' => 'Golden Retriever', 'color' => '#eebb77', 'emoji' => '🐕', 'weight' => 30, 'size' => 'Grande'],
        ['name' => 'Misi',  'breed' => 'Siamés',         'color' => '#c9b8a0', 'emoji' => '🐈', 'weight' => 4,  'size' => 'Pequeño'],
        ['name' => 'Nala',  'breed' => 'Persa',          'color' => '#e8d5b7', 'emoji' => '🐈', 'weight' => 5,  'size' => 'Mediano'],
        ['name' => 'Bigotes', 'breed' => 'Maine Coon',   'color' => '#8b7d6b', 'emoji' => '🐈', 'weight' => 7,  'size' => 'Grande'],
        ['name' => 'Canela', 'breed' => 'Bengalí',       'color' => '#d4a054', 'emoji' => '🐈', 'weight' => 5,  'size' => 'Mediano'],
        ['name' => 'Pelusa', 'breed' => 'Cruce',         'color' => '#f5f0e1', 'emoji' => '🐈', 'weight' => 3,  'size' => 'Pequeño'],
        ['name' => 'Milo',   'breed' => 'Husky',         'color' => '#a0b2c6', 'emoji' => '🐕', 'weight' => 22, 'size' => 'Grande'],
    ];

    public function definition(): array
    {
        $pet = $this->faker->unique()->randomElement(static::$dogs);
        $species = in_array($pet['name'], ['Misi', 'Nala', 'Bigotes', 'Canela', 'Pelusa']) ? 'Gato' : 'Perro';

        $descriptions = [
            "{$pet['name']} es un {$species} cariñoso y juguetón que busca un hogar donde lo cuiden y le den mucho amor. Es muy sociable y se lleva bien con niños y otros animales.",
            "{$pet['name']} fue rescatado de la calle y desde entonces ha mostrado una personalidad increíblemente dulce. Le encanta dar paseos y dormir al sol.",
            "{$pet['name']} es un {$species} tranquilo y educado, ideal para departamento. Ya está vacunado y desparasitado. Se entrega con todos sus cuidados al día.",
            "{$pet['name']} tiene una mirada que enamora. Es juguetón, le encanta correr y jugar con pelotas. Será el compañero perfecto para alguien activo.",
            "{$pet['name']} llegó al refugio muy pequeño y ahora está listo para encontrar una familia. Es agradecido, obediente y muy limpio.",
        ];

        $cities = ['Medellín', 'Bogotá', 'Cali', 'Envigado', 'Bello', 'Itagüí', 'Sabaneta', 'Manizales'];

        return [
            'name' => $pet['name'],
            'species' => $species,
            'breed' => $pet['breed'],
            'age' => rand(1, 8),
            'age_unit' => 'años',
            'gender' => $this->faker->randomElement(['Macho', 'Hembra']),
            'city' => $this->faker->randomElement($cities),
            'weight' => $pet['weight'],
            'size' => $pet['size'],
            'shelter_id' => Shelter::factory()->create()->id,
            'status' => 'disponible',
            'description' => $this->faker->randomElement($descriptions),
            'image' => ($species === 'Gato'
                ? $this->faker->randomElement([
                    'https://cdn2.thecatapi.com/images/MTg5NjAzMw.jpg',
                    'https://cdn2.thecatapi.com/images/dv4.jpg',
                    'https://cdn2.thecatapi.com/images/b4r.jpg',
                    'https://cdn2.thecatapi.com/images/GcZbVDVi8.jpg',
                    'https://cdn2.thecatapi.com/images/cfq.jpg',
                ])
                : $this->faker->randomElement([
                    'https://s3.us-west-2.amazonaws.com/cdn2.thedogapi.com/images/0mUBUQ68p.jpg',
                    'https://storage.googleapis.com/cdn4.thedogapi.com/optimized/fjKm1CMZDK.jpg',
                    'https://storage.googleapis.com/cdn4.thedogapi.com/optimized/b1g9nswl5B.jpg',
                    'https://s3.us-west-2.amazonaws.com/cdn2.thedogapi.com/images/H1LsFdnrm.jpg',
                    'https://storage.googleapis.com/cdn4.thedogapi.com/optimized/KJ4LZb2yXL.jpg',
                    'https://storage.googleapis.com/cdn4.thedogapi.com/optimized/Rvl1BeqaRB.jpg',
                    'https://storage.googleapis.com/cdn4.thedogapi.com/optimized/oWf6AU5Xtw.jpg',
                ])),
            'emoji' => $pet['emoji'],
            'color' => $pet['color'],
            'user_id' => User::inRandomOrder()->first()->id ?? 1,
            'vacunado' => true,
            'castrado' => $this->faker->boolean(70),
            'sociable' => $this->faker->boolean(80),
            'entrenado' => $this->faker->boolean(40),
            'phone' => $this->faker->randomElement(['310 123 4567', '320 765 4321', '300 998 8776', '']),
        ];
    }
}
