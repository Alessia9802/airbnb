<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // "icon" è il nome di un'icona lucide-vue-next.
        $services = [
            ['name' => 'Wi-Fi', 'icon' => 'Wifi'],
            ['name' => 'Aria condizionata', 'icon' => 'AirVent'],
            ['name' => 'Cucina', 'icon' => 'CookingPot'],
            ['name' => 'Parcheggio gratuito', 'icon' => 'Car'],
            ['name' => 'Piscina', 'icon' => 'Waves'],
            ['name' => 'Lavatrice', 'icon' => 'WashingMachine'],
            ['name' => 'TV', 'icon' => 'Tv'],
            ['name' => 'Animali ammessi', 'icon' => 'PawPrint'],
            ['name' => 'Vista mare', 'icon' => 'Sunset'],
            ['name' => 'Spazio di lavoro', 'icon' => 'Laptop'],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service);
        }
    }
}
