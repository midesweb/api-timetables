<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AuthTestUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'dev@apitimetables.test'],
            [
                'name' => 'Dev Tester',
                'password' => bcrypt('password123'),
            ]
        );

        $token = $user->createToken('scribe_token')->plainTextToken;

        echo "\n\n📘 Añade esta línea a tu .env:\n";
        echo "\"SCRIBE_AUTH_KEY=Bearer {$token}\"\n\n";
    }
}
