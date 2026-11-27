<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    public function run(): void
    {
        $serviceIds = Service::pluck('id');
        $hosts = User::all();

        Apartment::factory(24)
            ->recycle($hosts)
            ->create()
            ->each(fn (Apartment $apartment) => $apartment->services()->attach(
                $serviceIds->random(fake()->numberBetween(3, 7))
            ));
    }
}
