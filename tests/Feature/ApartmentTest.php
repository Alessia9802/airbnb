<?php

namespace Tests\Feature;

use App\Models\Apartment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ApartmentTest extends TestCase
{
    use RefreshDatabase;

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Loft in centro',
            'description' => 'Un bel loft.',
            'rooms' => 2,
            'beds' => 3,
            'bathrooms' => 1,
            'square_meters' => 60,
            'address' => 'Via Roma 1',
            'city' => 'Milano',
            'price_per_night' => 90,
            'is_visible' => true,
        ], $overrides);
    }

    public function test_home_lists_only_visible_apartments_and_filters_by_city(): void
    {
        Apartment::factory()->create(['city' => 'Roma', 'is_visible' => true]);
        Apartment::factory()->create(['city' => 'Milano', 'is_visible' => true]);
        Apartment::factory()->create(['city' => 'Roma', 'is_visible' => false]);

        $this->get('/')->assertInertia(fn (Assert $page) => $page->component('Home')->has('apartments.data', 2));
        $this->get('/?q=roma')->assertInertia(fn (Assert $page) => $page->has('apartments.data', 1));
    }

    public function test_hidden_apartment_is_visible_only_to_its_host(): void
    {
        $apartment = Apartment::factory()->create(['is_visible' => false]);

        $this->get(route('apartments.show', $apartment))->assertNotFound();
        $this->actingAs($apartment->host)->get(route('apartments.show', $apartment))->assertOk();
    }

    public function test_host_can_create_an_apartment_with_cover_and_services(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $services = Service::insert([['name' => 'Wi-Fi', 'icon' => 'Wifi'], ['name' => 'TV', 'icon' => 'Tv']]);

        $this->actingAs($user)
            ->post(route('host.apartments.store'), $this->validData([
                'cover' => UploadedFile::fake()->image('casa.jpg'),
                'services' => Service::pluck('id')->all(),
            ]))
            ->assertRedirect(route('host.apartments.index'));

        $apartment = $user->apartments()->first();
        $this->assertSame('loft-in-centro', $apartment->slug);
        $this->assertCount(2, $apartment->services);
        Storage::disk('public')->assertExists($apartment->cover_image);
    }

    public function test_invalid_data_is_rejected(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('host.apartments.store'), $this->validData(['title' => '', 'rooms' => 0]))
            ->assertSessionHasErrors(['title', 'rooms']);
    }

    public function test_host_cannot_edit_or_delete_someone_elses_apartment(): void
    {
        $apartment = Apartment::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($other)->get(route('host.apartments.edit', $apartment))->assertForbidden();
        $this->actingAs($other)->put(route('host.apartments.update', $apartment), $this->validData())->assertForbidden();
        $this->actingAs($other)->delete(route('host.apartments.destroy', $apartment))->assertForbidden();
        $this->assertModelExists($apartment);
    }

    public function test_host_can_update_and_delete_own_apartment(): void
    {
        $apartment = Apartment::factory()->create();

        $this->actingAs($apartment->host)
            ->put(route('host.apartments.update', $apartment), $this->validData(['title' => 'Nuovo titolo']))
            ->assertRedirect(route('host.apartments.index'));
        $this->assertSame('nuovo-titolo', $apartment->fresh()->slug);

        $this->actingAs($apartment->host)->delete(route('host.apartments.destroy', $apartment->fresh()));
        $this->assertModelMissing($apartment);
    }
}
