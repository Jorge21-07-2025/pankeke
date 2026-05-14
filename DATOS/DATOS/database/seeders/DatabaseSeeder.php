<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shelter;
use App\Models\Pet;
use App\Models\AdoptionRequest;
use App\Models\Message;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Carlos García',
                'email' => 'carlos@example.com',
                'password' => bcrypt('123456'),
                'phone' => '310 111 2233',
            ]);

            User::factory()->create([
                'name' => 'María López',
                'email' => 'maria@example.com',
                'password' => bcrypt('123456'),
                'phone' => '320 444 5566',
            ]);
        }

        if (Shelter::count() === 0) {
            Shelter::factory()->count(3)->create();
        }

        if (Pet::count() === 0) {
            Pet::factory()->count(12)->create();
        }

        if (AdoptionRequest::count() === 0) {
            $adoptions = [
                ['user_email' => 'maria@example.com', 'pet_name' => 'Luna',  'status' => 'aprobado'],
                ['user_email' => 'maria@example.com', 'pet_name' => 'Misi',  'status' => 'en_proceso'],
                ['user_email' => 'carlos@example.com', 'pet_name' => 'Coco',  'status' => 'en_proceso'],
                ['user_email' => 'carlos@example.com', 'pet_name' => 'Nala',  'status' => 'rechazado'],
            ];

            foreach ($adoptions as $a) {
                $user = User::where('email', $a['user_email'])->first();
                $pet = Pet::where('name', $a['pet_name'])->first();
                if ($user && $pet) {
                    AdoptionRequest::create([
                        'user_id' => $user->id,
                        'pet_id' => $pet->id,
                        'message' => 'Hola, me encantaría darle un hogar a ' . $pet->name . '. Tengo experiencia con mascotas y un espacio amplio y seguro.',
                        'phone' => $user->phone,
                        'status' => $a['status'],
                    ]);
                }
            }
        }

        if (Message::count() === 0) {
            Message::create([
                'from_user_id' => User::where('email', 'maria@example.com')->first()->id,
                'to_user_id' => User::where('email', 'carlos@example.com')->first()->id,
                'pet_id' => Pet::where('name', 'Luna')->first()->id,
                'message' => '¡Hola! Me interesa mucho adoptar a Luna. ¿Podríamos coordinar una visita?',
                'read' => false,
            ]);

            Message::create([
                'from_user_id' => User::where('email', 'carlos@example.com')->first()->id,
                'to_user_id' => User::where('email', 'maria@example.com')->first()->id,
                'pet_id' => Pet::where('name', 'Luna')->first()->id,
                'message' => '¡Claro! Luna está disponible. ¿Te sirve el sábado en la mañana para conocerla?',
                'read' => true,
            ]);
        }
    }
}
