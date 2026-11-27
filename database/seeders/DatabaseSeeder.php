<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Utente demo (password: "password", vedi UserFactory).
        User::factory()->create([
            'name' => 'Host Demo',
            'email' => 'test@example.com',
        ]);

        User::factory(4)->create();

        $this->call([
            ServiceSeeder::class,
            ApartmentSeeder::class,
        ]);
    }
}
