<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Apartment extends Model
{
    /** @use HasFactory<\Database\Factories\ApartmentFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'rooms',
        'beds',
        'bathrooms',
        'square_meters',
        'address',
        'city',
        'latitude',
        'longitude',
        'price_per_night',
        'cover_image',
        'is_visible',
    ];

    protected $appends = ['cover_url'];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'price_per_night' => 'decimal:2',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Apartment $apartment) {
            if ($apartment->isDirty('title') || ! $apartment->slug) {
                $apartment->slug = static::uniqueSlug($apartment->title, $apartment->id);
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'alloggio';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** URL pubblico della copertina: accetta sia URL esterni (seeder) sia file caricati. */
    protected function coverUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->cover_image) {
                return null;
            }

            return Str::startsWith($this->cover_image, ['http://', 'https://'])
                ? $this->cover_image
                : Storage::disk('public')->url($this->cover_image);
        });
    }

    public function scopeVisible(Builder $query): void
    {
        $query->where('is_visible', true);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }
}
