<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApartmentController extends Controller
{
    /** Home pubblica: elenco degli alloggi visibili, con ricerca per città o titolo. */
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('q', ''));

        $apartments = Apartment::visible()
            ->when($search !== '', fn ($query) => $query->where(fn ($q) => $q
                ->where('city', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Home', [
            'apartments' => $apartments,
            'filters' => ['q' => $search],
        ]);
    }

    public function show(Request $request, Apartment $apartment): Response
    {
        // Un alloggio nascosto è visibile solo al suo host.
        abort_unless($apartment->is_visible || $apartment->user_id === $request->user()?->id, 404);

        return Inertia::render('Apartments/Show', [
            'apartment' => $apartment->load(['services', 'host:id,name,created_at']),
        ]);
    }
}
