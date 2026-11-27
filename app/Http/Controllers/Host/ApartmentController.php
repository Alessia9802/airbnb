<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApartmentRequest;
use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ApartmentController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Host/Apartments/Index', [
            'apartments' => $request->user()->apartments()->latest()->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Host/Apartments/Create', [
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    public function store(ApartmentRequest $request): RedirectResponse
    {
        $apartment = new Apartment($request->safe()->except(['cover', 'services']));
        $apartment->user_id = $request->user()->id;

        if ($request->hasFile('cover')) {
            $apartment->cover_image = $request->file('cover')->store('apartments', 'public');
        }

        $apartment->save();
        $apartment->services()->sync($request->validated('services', []));

        return to_route('host.apartments.index')->with('success', 'Alloggio creato.');
    }

    public function edit(Request $request, Apartment $apartment): Response
    {
        $this->ensureOwner($request, $apartment);

        return Inertia::render('Host/Apartments/Edit', [
            'apartment' => $apartment->load('services'),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    public function update(ApartmentRequest $request, Apartment $apartment): RedirectResponse
    {
        $apartment->fill($request->safe()->except(['cover', 'services']));

        if ($request->hasFile('cover')) {
            $this->deleteStoredCover($apartment);
            $apartment->cover_image = $request->file('cover')->store('apartments', 'public');
        }

        $apartment->save();
        $apartment->services()->sync($request->validated('services', []));

        return to_route('host.apartments.index')->with('success', 'Alloggio aggiornato.');
    }

    public function destroy(Request $request, Apartment $apartment): RedirectResponse
    {
        $this->ensureOwner($request, $apartment);

        $this->deleteStoredCover($apartment);
        $apartment->delete();

        return to_route('host.apartments.index')->with('success', 'Alloggio eliminato.');
    }

    private function ensureOwner(Request $request, Apartment $apartment): void
    {
        abort_unless($apartment->user_id === $request->user()->id, 403);
    }

    private function deleteStoredCover(Apartment $apartment): void
    {
        if ($apartment->cover_image && ! Str::startsWith($apartment->cover_image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($apartment->cover_image);
        }
    }
}
