<script setup lang="ts">
import { formatPrice } from '@/lib/format';
import type { Apartment } from '@/types';
import { Link } from '@inertiajs/vue3';
import { ImageOff } from 'lucide-vue-next';

defineProps<{ apartment: Apartment }>();
</script>

<template>
    <Link :href="route('apartments.show', apartment.slug)" class="group block">
        <div class="aspect-[4/3] overflow-hidden rounded-xl bg-muted">
            <img
                v-if="apartment.cover_url"
                :src="apartment.cover_url"
                :alt="apartment.title"
                loading="lazy"
                class="size-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
            <div v-else class="flex size-full items-center justify-center text-muted-foreground">
                <ImageOff class="size-8" />
            </div>
        </div>
        <div class="mt-3 space-y-0.5 text-sm">
            <p class="font-semibold">{{ apartment.city }}</p>
            <p class="line-clamp-1 text-muted-foreground">{{ apartment.title }}</p>
            <p class="text-muted-foreground">{{ apartment.rooms }} stanze · {{ apartment.beds }} letti</p>
            <p class="pt-1">
                <span class="font-semibold">{{ formatPrice(apartment.price_per_night) }}</span>
                <span class="text-muted-foreground"> / notte</span>
            </p>
        </div>
    </Link>
</template>
