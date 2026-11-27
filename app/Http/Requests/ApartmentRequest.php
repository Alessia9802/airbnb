<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $apartment = $this->route('apartment');

        return $apartment === null || $apartment->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'rooms' => ['required', 'integer', 'min:1', 'max:50'],
            'beds' => ['required', 'integer', 'min:1', 'max:100'],
            'bathrooms' => ['required', 'integer', 'min:1', 'max:20'],
            'square_meters' => ['required', 'integer', 'min:10', 'max:5000'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'price_per_night' => ['required', 'numeric', 'min:1', 'max:99999'],
            'is_visible' => ['boolean'],
            'cover' => ['nullable', 'image', 'max:4096'],
            'services' => ['array'],
            'services.*' => ['integer', 'exists:services,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'titolo',
            'description' => 'descrizione',
            'rooms' => 'stanze',
            'beds' => 'letti',
            'bathrooms' => 'bagni',
            'square_meters' => 'metri quadri',
            'address' => 'indirizzo',
            'city' => 'città',
            'price_per_night' => 'prezzo a notte',
            'cover' => 'immagine di copertina',
        ];
    }
}
