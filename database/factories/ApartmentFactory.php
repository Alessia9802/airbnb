<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Apartment>
 */
class ApartmentFactory extends Factory
{
    /** Città di esempio con coordinate approssimative del centro. */
    private const CITIES = [
        ['Roma', 41.9028, 12.4964],
        ['Milano', 45.4642, 9.1900],
        ['Firenze', 43.7696, 11.2558],
        ['Napoli', 40.8518, 14.2681],
        ['Venezia', 45.4408, 12.3155],
        ['Bologna', 44.4949, 11.3426],
        ['Torino', 45.0703, 7.6869],
        ['Palermo', 38.1157, 13.3615],
    ];

    private const TYPES = ['Loft', 'Appartamento', 'Monolocale', 'Attico', 'Bilocale', 'Casa vacanze', 'Suite'];

    private const ADJECTIVES = ['luminoso', 'accogliente', 'con vista', 'nel centro storico', 'moderno', 'con terrazza', 'silenzioso'];

    public function definition(): array
    {
        [$city, $lat, $lng] = fake()->randomElement(self::CITIES);
        $rooms = fake()->numberBetween(1, 5);

        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElement(self::TYPES).' '.fake()->randomElement(self::ADJECTIVES).' a '.$city,
            'description' => fake()->paragraphs(3, true),
            'rooms' => $rooms,
            'beds' => fake()->numberBetween($rooms, $rooms * 2),
            'bathrooms' => fake()->numberBetween(1, max(1, intdiv($rooms, 2) + 1)),
            'square_meters' => fake()->numberBetween(25 * $rooms, 45 * $rooms),
            'address' => fake()->streetAddress(),
            'city' => $city,
            'latitude' => $lat + fake()->randomFloat(4, -0.03, 0.03),
            'longitude' => $lng + fake()->randomFloat(4, -0.03, 0.03),
            'price_per_night' => fake()->numberBetween(45, 320),
            'cover_image' => 'https://picsum.photos/seed/'.fake()->unique()->uuid().'/800/600',
            'is_visible' => fake()->boolean(90),
        ];
    }
}
