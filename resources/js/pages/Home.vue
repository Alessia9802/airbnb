<script setup lang="ts">
import ApartmentCard from '@/components/ApartmentCard.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { Apartment, Paginated } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    apartments: Paginated<Apartment>;
    filters: { q: string };
}>();
</script>

<template>
    <Head title="Trova il tuo alloggio" />

    <PublicLayout :search="filters.q">
        <div class="mb-6 flex items-baseline justify-between gap-4">
            <h1 class="text-2xl font-semibold">
                <template v-if="filters.q">Alloggi per «{{ filters.q }}»</template>
                <template v-else>Alloggi in evidenza</template>
            </h1>
            <p class="text-sm text-muted-foreground">{{ apartments.total }} risultati</p>
        </div>

        <div v-if="apartments.data.length" class="grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <ApartmentCard v-for="apartment in apartments.data" :key="apartment.id" :apartment="apartment" />
        </div>

        <div v-else class="rounded-xl border border-dashed p-12 text-center">
            <p class="font-medium">Nessun alloggio trovato</p>
            <p class="mt-1 text-sm text-muted-foreground">Prova a cercare un'altra città.</p>
            <Link :href="route('home')" class="mt-4 inline-block text-sm font-medium underline">Mostra tutti gli alloggi</Link>
        </div>

        <nav v-if="apartments.last_page > 1" class="mt-12 flex flex-wrap justify-center gap-1" aria-label="Paginazione">
            <template v-for="link in apartments.links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    preserve-scroll
                    class="rounded-full px-3.5 py-2 text-sm hover:bg-muted"
                    :class="{ 'bg-foreground text-background hover:bg-foreground': link.active }"
                    ><span v-html="link.label"
                /></Link>
                <span v-else class="px-3.5 py-2 text-sm text-muted-foreground" v-html="link.label" />
            </template>
        </nav>
    </PublicLayout>
</template>
