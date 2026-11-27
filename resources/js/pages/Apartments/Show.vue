<script setup lang="ts">
import ServiceIcon from '@/components/ServiceIcon.vue';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { formatPrice } from '@/lib/format';
import type { Apartment } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Bath, BedDouble, DoorOpen, ImageOff, MapPin, Ruler } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{ apartment: Apartment }>();

const hostSince = computed(() =>
    props.apartment.host ? new Date(props.apartment.host.created_at).toLocaleDateString('it-IT', { month: 'long', year: 'numeric' }) : '',
);

const mapUrl = computed(() => {
    const { latitude, longitude, address, city } = props.apartment;
    const q = latitude && longitude ? `${latitude},${longitude}` : `${address}, ${city}`;
    return `https://www.openstreetmap.org/search?query=${encodeURIComponent(q)}`;
});
</script>

<template>
    <Head :title="apartment.title" />

    <PublicLayout>
        <div v-if="!apartment.is_visible" class="mb-4 rounded-lg bg-amber-100 px-4 py-2 text-sm text-amber-900">
            Questo alloggio è nascosto: lo vedi solo tu perché sei l'host.
        </div>

        <h1 class="text-2xl font-semibold sm:text-3xl">{{ apartment.title }}</h1>
        <a :href="mapUrl" target="_blank" rel="noopener" class="mt-1 inline-flex items-center gap-1 text-sm text-muted-foreground underline">
            <MapPin class="size-4" /> {{ apartment.address }}, {{ apartment.city }}
        </a>

        <div class="mt-6 aspect-[16/9] overflow-hidden rounded-2xl bg-muted sm:aspect-[21/9]">
            <img v-if="apartment.cover_url" :src="apartment.cover_url" :alt="apartment.title" class="size-full object-cover" />
            <div v-else class="flex size-full items-center justify-center text-muted-foreground"><ImageOff class="size-10" /></div>
        </div>

        <div class="mt-8 grid gap-10 lg:grid-cols-[1fr_22rem]">
            <div class="space-y-8">
                <div class="border-b pb-6">
                    <h2 class="text-xl font-semibold">Host: {{ apartment.host?.name }}</h2>
                    <p class="text-sm text-muted-foreground">Host da {{ hostSince }}</p>
                </div>

                <ul class="grid grid-cols-2 gap-4 border-b pb-6 text-sm sm:grid-cols-4">
                    <li class="flex items-center gap-2"><DoorOpen class="size-5" /> {{ apartment.rooms }} stanze</li>
                    <li class="flex items-center gap-2"><BedDouble class="size-5" /> {{ apartment.beds }} letti</li>
                    <li class="flex items-center gap-2"><Bath class="size-5" /> {{ apartment.bathrooms }} bagni</li>
                    <li class="flex items-center gap-2"><Ruler class="size-5" /> {{ apartment.square_meters }} m²</li>
                </ul>

                <section class="border-b pb-6">
                    <h2 class="mb-3 text-lg font-semibold">Descrizione</h2>
                    <p class="whitespace-pre-line leading-relaxed text-muted-foreground">{{ apartment.description }}</p>
                </section>

                <section v-if="apartment.services?.length">
                    <h2 class="mb-4 text-lg font-semibold">Cosa troverai</h2>
                    <ul class="grid gap-3 sm:grid-cols-2">
                        <li v-for="service in apartment.services" :key="service.id" class="flex items-center gap-3">
                            <ServiceIcon :name="service.icon" class="size-5" />
                            {{ service.name }}
                        </li>
                    </ul>
                </section>
            </div>

            <aside class="h-fit rounded-2xl border p-6 shadow-lg lg:sticky lg:top-24">
                <p>
                    <span class="text-2xl font-semibold">{{ formatPrice(apartment.price_per_night) }}</span>
                    <span class="text-muted-foreground"> / notte</span>
                </p>
                <Button class="mt-6 w-full rounded-lg bg-rose-500 py-6 text-base hover:bg-rose-600" disabled>Prenota</Button>
                <p class="mt-3 text-center text-xs text-muted-foreground">Le prenotazioni arriveranno in una prossima versione.</p>
            </aside>
        </div>
    </PublicLayout>
</template>
